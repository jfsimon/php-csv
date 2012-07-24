<?php

namespace Csv\Resource\Stream;

use Csv\Resource\ReaderInterface;

/**
 * Resource
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Buffer
{
    /**
     * @var string|null
     */
    private $lineBreak;

    /**
     * @var string
     */
    private $content;

    /**
     * @var int|null
     */
    private $lbPosition;

    /**
     * @var bool
     */
    private $lbChecked;

    /**
     * @var string
     */
    private $lbString;

    /**
     * Constructor.
     *
     * @param string|null $lineBreak
     */
    public function __construct($lineBreak = ReaderInterface::LB_AUTO)
    {
        $this->lineBreak = $lineBreak;
        $this->clear();
    }

    /**
     * Clears buffer.
     *
     * @return Buffer Current buffer instance
     */
    public function clear()
    {
        $this->content    = '';
        $this->lbPosition = null;
        $this->lbChecked  = false;
        $this->lbString   = '';

        return $this;
    }

    /**
     * Appends content to buffer.
     *
     * @param string $content Content to append
     *
     * @return ReaderInterface Current reader instance
     */
    public function append($content)
    {
        if (ReaderInterface::LB_AUTO === $this->lineBreak && "\r" === $this->lbString && "\n" === substr($content, 0, 1)) {
            $content = substr($content, 1);
        }

        $this->content.= $content;

        if ($this->lbChecked && false === $this->lbPosition) {
            $this->lbChecked = false;
        }

        return $this;
    }

    /**
     * Reads buffer content until a line break is found.
     *
     * @return string|null String if line break is found, null otherwise
     *
     * @throws \RuntimeException If no line break found
     */
    public function readUntilLineBreak()
    {
        if (!$this->lbChecked) {
            if (!$this->hasLineBreak()) {
                throw new \RuntimeException('No line break found.');
            }
        }

        if (null === $this->lbPosition) {
            throw new \RuntimeException('No line break found.');
        }

        $line = substr($this->content, 0, $this->lbPosition);
        $this->content = substr($this->content, $this->lbPosition + strlen($this->lbString));

        $this->lbChecked  = false;
        $this->lbPosition = null;

        return $line;
    }

    /**
     * Returns true if buffer contains a line break, false otherwise.
     *
     * @return bool
     */
    public function hasLineBreak()
    {
        if ($this->lbChecked) {
            return false !== $this->lbPosition;
        }

        $this->lbChecked = true;

        if (ReaderInterface::LB_AUTO === $this->lineBreak) {
            foreach (array(ReaderInterface::LB_WINDOWS, ReaderInterface::LB_UNIX, ReaderInterface::LB_MAC) as $lineBreak) {
                $position = strpos($this->content, $lineBreak);

                if (false !== $position) {
                    break;
                }
            }
        } else {
            $lineBreak = $this->lineBreak;
            $position  = strpos($this->content, $this->lineBreak);
        }

        $this->lbPosition = $position;

        if (false === $position) {
            return false;
        }

        $this->lbString = $lineBreak;

        return true;
    }

    /**
     * Returns true if buffer is empty, false otherwise.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return '' === $this->content;
    }

    /**
     * Returns buffer content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns last line break found.
     *
     * @return string
     */
    public function getLastLineBreak()
    {
        return $this->lbString;
    }
}
