<?php

namespace Csv\Resource;

use Csv\Exception\ResourceErrorException;
use Csv\Exception\ReadingFinishedException;

/**
 * Interface for resource writer.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface WriterInterface extends ResourceInterface
{
    /**
     * Writes content to resource and appends a line break.
     *
     * @param string $content
     *
     * @return WriterInterface Current instance
     *
     * @throws ResourceErrorException If write failed
     */
    function writeLine($content);
}
