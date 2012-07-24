<?php

namespace Csv\Engine;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
abstract class WorkerIterator implements \Iterator
{
    /**
     * @var int
     */
    private $index = 0;

    /**
     * @var bool
     */
    private $finished = false;

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->index ++;
        $this->build();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return !$this->finished;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        if ($this->index > 0) {
            $this->reiterate();
        }

        $this->build();
    }

    /**
     * Marks iterator as finished.
     */
    protected function finish()
    {
        $this->finished = true;
    }

    /**
     * Builds value to iterate.
     */
    protected abstract function build();

    /**
     * Rewinds iterator after iterations.
     */
    protected abstract function reiterate();
}
