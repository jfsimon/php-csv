<?php

namespace Csv\Exception;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ResourceErrorException extends \RuntimeException
{
    /**
     * @var mixed
     */
    private $resource;

    /**
     * @param mixed $resource
     *
     * @return ResourceErrorException
     */
    public static function invalidResource($resource)
    {
        return new self($resource, 'Given resource "'.var_export($resource, true).'" is not valid.');
    }

    /**
     * @param mixed $resource
     *
     * @return ResourceErrorException
     */
    public static function closingFailed($resource)
    {
        return new self($resource, 'Failed to close resource "'.var_export($resource, true).'".');
    }

    /**
     * @param mixed $resource
     *
     * @return ResourceErrorException
     */
    public static function writingFailed($resource)
    {
        return new self($resource, 'Failed to write resource "'.var_export($resource, true).'".');
    }

    /**
     * @param mixed  $resource
     * @param string $message
     */
    public function __construct($resource, $message)
    {
        $this->resource = $resource;
        parent::__construct($message);
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }
}
