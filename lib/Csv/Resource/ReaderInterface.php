<?php

namespace Csv\Resource;

use Csv\Exception\ResourceErrorException;
use Csv\Exception\ReadingFinishedException;

/**
 * Interface for resource reader.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface ReaderInterface extends ResourceInterface
{
    const LB_AUTO = null;

    /**
     * Reads resource content as string until EOF or line break found.
     *
     * @return string
     *
     * @throws ReadingFinishedException If reading is finished
     */
    function readLine();
}
