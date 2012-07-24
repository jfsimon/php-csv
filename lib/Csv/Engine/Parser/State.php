<?php

namespace Csv\Engine\Parser;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class State
{
    /**
     * @var bool
     */
    private $enclosed;

    /**
     * @var bool
     */
    private $escaped;

    /**
     * @var string
     */
    private $currentCell;

    /**
     * @var array
     */
    private $currentRow;

    /**
     *
     */
    public function __construct()
    {
        $this->enclosed    = false;
        $this->escaped     = false;
        $this->currentCell = '';
        $this->currentRow  = array();
    }

    /**
     * @param boolean $enclosed
     *
     * @return State
     */
    public function setEnclosed($enclosed)
    {
        $this->enclosed = $enclosed;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnclosed()
    {
        return $this->enclosed;
    }

    /**
     * @param boolean $escaped
     *
     * @return State
     */
    public function setEscaped($escaped)
    {
        $this->escaped = $escaped;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEscaped()
    {
        return $this->escaped;
    }

    /**
     * @param $content
     *
     * @return State
     */
    public function addContent($content)
    {
        $this->currentCell.= $content;

        return $this;
    }

    /**
     * @return State
     */
    public function nextCell()
    {
        $this->currentRow[] = $this->currentCell;
        $this->currentCell  = '';

        return $this;
    }

    /**
     * @return array
     */
    public function fetchRow()
    {
        $this->currentRow[] = $this->currentCell;

        return $this->currentRow;
    }
}
