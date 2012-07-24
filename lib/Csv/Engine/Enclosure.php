<?php

namespace Csv\Engine;

/**
 * Content enclosure.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Enclosure
{
    /**
     * @var string
     */
    public $start;

    /**
     * @var string
     */
    public $end;

    /**
     * @var string
     */
    public $escape;

    /**
     * Constructor.
     *
     * @param string      $start
     * @param string      $escape
     * @param string|null $end
     */
    public function __construct($start, $escape = '\\', $end = null)
    {
        $this->start  = $start;
        $this->end    = null === $end ? $start : $end;
        $this->escape = $escape;
    }

    /**
     * Encloses content.
     *
     * @param string $content   Content to enclose
     * @param array  $protected Strings to protect with enclosure
     * @param bool   $force     Force enclosure
     *
     * @return string Enclosed content
     */
    public function enclose($content, array $protected, $force = false)
    {
        $protected[] = $this->start;
        $protected[] = $this->end;

        if ($force || $this->shouldEnclose($content, array_unique($protected))) {
            return $this->start.str_replace($this->end, $this->escape.$this->end, $content).$this->end;
        }

        return $content;
    }

    /**
     * Discloses content if needed.
     *
     * @param string $content Enclosed content
     *
     * @return string Disclosed content
     *
     * @throws \RuntimeException If closing boundary is escaped
     */
    public function disclose($content)
    {
        if (substr($content, 0, strlen($this->start)) === $this->start && substr($content, -strlen($this->end)) === $this->end) {
            $content = substr($content, strlen($this->start), -strlen($this->end));

            if ($this->escape === substr($content, -1)) {
                throw new \RuntimeException('Last boundary is escaped.');
            }

            return str_replace($this->escape.$this->end, $this->end, $content);
        }

        return $content;
    }

    /**
     * Defines whether content should be enclosed or not.
     *
     * @param string $content   Content to enclose
     * @param array  $protected Strings to protect
     *
     * @return bool True if content should be enclosed
     */
    private function shouldEnclose($content, array $protected)
    {
        foreach ($protected as $value) {
            if (false !== strpos($content, $value)) {
                return true;
            }
        }

        return false;
    }
}
