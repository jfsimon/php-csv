<?php

namespace Csv\Engine\Tokenizer;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Matcher
{
    /**
     * @var array
     */
    private $types;

    /**
     * @var array
     */
    private $matches;

    /**
     * @param array $types
     */
    public function __construct(array $types)
    {
        $this->types   = $types;
        $this->matches = array();
    }

    /**
     * @param string $content
     */
    public function build($content)
    {
        foreach ($this->types as $search => $type) {
            foreach ($this->find($search, $content) as $position) {
                if (isset($this->matches[$position]) && strlen($this->matches[$position]['content']) > strlen($search)) {
                    continue;
                }

                $this->matches[$position] = array(
                    'content'  => $search,
                    'type'     => $type,
                    'position' => $position,
                    'length'   => strlen($search),
                );
            }
        }

        ksort($this->matches);
    }

    /**
     * @return array|null
     */
    public function fetch()
    {
        if (0 === count($this->matches)) {
            return null;
        }

        $match = reset($this->matches);

        for ($offset = 0; $offset < $match['length']; $offset ++) {
            unset($this->matches[$match['position'] + $offset]);
        }

        return $match;
    }

    /**
     * @param string $search
     * @param string $content
     *
     * @return array
     */
    private function find($search, $content)
    {
        $positions = array();
        $position  = -1;

        while (false !== $position = strpos($content, $search, $position+1)) {
            $positions[] = $position;
        }

        return $positions;
    }
}
