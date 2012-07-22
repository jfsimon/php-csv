<?php

namespace Csv\Resource\Stream;

use Csv\Resource\ReaderInterface;
use Csv\Exception\ReadingFinishedException;

/**
 * Stream lines reader.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Reader extends Stream implements ReaderInterface
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
    public function readLine()
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
                return $this->buffer->readUntilLineBreak();
            }

            if (null === $this->getResource()) {
                $remain = $this->buffer->getContent();
                $this->buffer->clear();

                return $remain;
            }
        }
    }
}
