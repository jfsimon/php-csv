<?php

namespace Csv\Resource;

use Csv\Exception\ResourceErrorException;
use Csv\Exception\ReadingFinishedException;

/**
 * Base interface for resource handlers.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface WorkerInterface
{
    const LB_UNIX    = "\n";
    const LB_MAC     = "\r";
    const LB_WINDOWS = "\r\n";

    /**
     * Opens a resource.
     *
     * @param mixed $resource A resource
     *
     * @return WorkerInterface Current instance
     *
     * @throws ResourceErrorException If resource is not valid
     */
    function open($resource);

    /**
     * Closes open resource if exists.
     *
     * @return WorkerInterface Current instance
     *
     * @throws ResourceErrorException If resource could not be closed
     */
    function close();

    /**
     * Returns open resource.
     *
     * @return mixed
     */
    function getResource();
}
