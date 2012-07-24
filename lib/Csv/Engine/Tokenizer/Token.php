<?php

namespace Csv\Engine\Tokenizer;

/**
 * Token.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Token
{
    // the basics
    const CONTENT    = 1;
    const SEPARATOR  = 2;
    const LINE_BREAK = 3;

    // simple enclosure
    const ENCLOSURE_START    = 11;
    const ENCLOSURE_END      = 12;
    const ENCLOSURE_BOUNDARY = 13;

    // escaped enclosure
    const ENCLOSURE_ESCAPED_START    = 14;
    const ENCLOSURE_ESCAPED_END      = 15;
    const ENCLOSURE_ESCAPED_BOUNDARY = 16;

    // enclosure special cases
    const ENCLOSURE_TRIPLE_START    = 17;
    const ENCLOSURE_TRIPLE_END      = 18;
    const ENCLOSURE_TRIPLE_BOUNDARY = 19;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * Constructor.
     *
     * @param int    $type
     * @param string $content
     */
    public function __construct($type, $content)
    {
        $this->type    = $type;
        $this->content = $content;
    }

    /**
     * Tests if token is given type.
     *
     * @param int $type Type to test
     *
     * @return bool
     */
    public function is($type)
    {
        if (self::ENCLOSURE_BOUNDARY === $this->type && (self::ENCLOSURE_START === $type || self::ENCLOSURE_END === $type)) {
            return true;
        }

        if (self::ENCLOSURE_ESCAPED_BOUNDARY === $this->type && (self::ENCLOSURE_ESCAPED_START === $type || self::ENCLOSURE_ESCAPED_END === $type)) {
            return true;
        }

        return $type === $this->type;
    }

    /**
     * Resturns token content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
