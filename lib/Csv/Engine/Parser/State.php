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
     * Returns true if current row is empty.
     *
     * @return bool
     */
    public function isRowStart()
    {
        return '' === $this->currentRow;
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
        $row   = $this->currentRow;
        $row[] = $this->currentCell;
        $this->currentRow = array();

        return $row;
    }
}
