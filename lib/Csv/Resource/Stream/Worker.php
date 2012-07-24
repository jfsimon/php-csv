<?php

namespace Csv\Resource\Stream;

use Csv\Exception\ResourceErrorException;

/**
 * Base stream class.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Worker
{
    /**
     * @var resource|null
     */
    private $resource = null;

    /**
     * Opens a resource to bind to current stream instance.
     *
     * @param resource $resource A resource pointer
     *
     * @return Worker Current instance
     *
     * @throws ResourceErrorException If resource is not valid
     */
    public function open($resource)
    {
        if (!is_resource($resource)) {
            throw ResourceErrorException::invalidResource($resource);
        }

        $this->resource = $resource;

        return $this;
    }

    /**
     * Closes bound resource if exists.
     *
     * @return Worker Current instance
     *
     * @throws ResourceErrorException If resource could not be closed
     */
    public function close()
    {
        if (null !== $this->resource) {
            if (fclose($this->resource)) {
                $this->resource = null;
            } else {
                throw ResourceErrorException::closingFailed($this->resource);
            }
        }

        return $this;
    }

    /**
     * Returns bound resource if exists.
     *
     * @return resource|null
     */
    public function getResource()
    {
        return $this->resource;
    }
}
