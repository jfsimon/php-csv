<?php

namespace Csv\Engine\Tokenizer;

/**
 * Matcher.
 *
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
     * Constructor.
     *
     * @param array $types Token types.
     */
    public function __construct(array $types)
    {
        $this->types   = $types;
        $this->matches = array();
    }

    /**
     * Builds matches.
     *
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
     * Fetches next match.
     *
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
     * Find positions of search in content.
     *
     * @param string $search  String to search
     * @param string $content Content to search in
     *
     * @return array An array of positions
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
