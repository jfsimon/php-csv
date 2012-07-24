<?php

namespace Csv\Engine\Parser;

/**
 * Parser state.
 *
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
     * Constructor.
     */
    public function __construct()
    {
        $this->enclosed    = false;
        $this->escaped     = false;
        $this->currentCell = '';
        $this->currentRow  = array();
    }

    /**
     * Writes "enclosed" state.
     *
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
     * Reads "enclosed" state.
     *
     * @return boolean
     */
    public function isEnclosed()
    {
        return $this->enclosed;
    }

    /**
     * Writes "escaped" state.
     *
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
     * Reads "escaped" state.
     *
     * @return boolean
     */
    public function isEscaped()
    {
        return $this->escaped;
    }

    /**
     * Adds content to current cell.
     *
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
     * Adds current cell to current row and creates a new cell.
     *
     * @return State
     */
    public function nextCell()
    {
        $this->currentRow[] = $this->currentCell;
        $this->currentCell  = '';

        return $this;
    }

    /**
     * Fetches current row.
     *
     * @return array
     */
    public function fetchRow()
    {
        $this->currentRow[] = $this->currentCell;

        return $this->currentRow;
    }
}
