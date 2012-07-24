<?php

namespace Csv\Resource\Stream;

use Csv\Resource\WriterInterface;
use Csv\Exception\ResourceErrorException;

/**
 * Stream line writer.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Writer extends Worker implements WriterInterface
{
    /**
     * @var string
     */
    private $lineBreak;

    /**
     * Constructor.
     *
     * @param string $lineBreak
     */
    public function __construct($lineBreak)
    {
        $this->lineBreak = $lineBreak;
    }

    /**
     * {@inheritdoc}
     */
    public function writeLine($content)
    {
        $size = fwrite($this->getResource(), $content.$this->lineBreak);

        if (false === $size) {
            ResourceErrorException::writingFailed($this->getResource());
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLineBreak()
    {
        return $this->lineBreak;
    }
}
