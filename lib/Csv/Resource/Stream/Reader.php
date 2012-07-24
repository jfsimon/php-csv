<?php

namespace Csv\Resource\Stream;

use Csv\Resource\ReaderInterface;
use Csv\Exception\ReadingFinishedException;

/**
 * Stream lines reader.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Reader extends Worker implements ReaderInterface
{
    /**
     * @var int
     */
    private $chunkSize;

    /**
     * @var Buffer
     */
    private $buffer;

    /**
     * @param int    $chunkSize
     * @param string $lineBreak
     */
    public function __construct($chunkSize = 1024, $lineBreak = self::LB_AUTO)
    {
        $this->chunkSize = $chunkSize;
        $this->buffer    = new Buffer($lineBreak);
    }

    /**
     * {@inheritdoc}
     */
    public function open($resource)
    {
        parent::open($resource);
        $this->buffer->clear();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function readLine($removeLineBreak = false)
    {
        if (null === $this->getResource() && $this->buffer->isEmpty()) {
            throw new ReadingFinishedException();
        }

        while (true) {
            if (null !== $this->getResource()) {
                $this->buffer->append(fread($this->getResource(), $this->chunkSize));

                if (feof($this->getResource())) {
                    $this->close();
                }
            }

            if ($this->buffer->hasLineBreak()) {
                $line = $this->buffer->readUntilLineBreak();

                return $removeLineBreak ? $line : $line.$this->buffer->getLastLineBreak();
            }

            if (null === $this->getResource()) {
                $remain = $this->buffer->getContent();
                $this->buffer->clear();

                return $remain;
            }
        }
    }
}
