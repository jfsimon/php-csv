<?php

namespace Csv\Engine\Tokenizer;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Token
{
    const CONTENT            = 0;
    const ENCLOSURE_START    = 1;
    const ENCLOSURE_END      = 2;
    const ENCLOSURE_BOUNDARY = 3;
    const ENCLOSURE_ESCAPE   = 4;
    const SEPARATOR          = 5;
    const LINE_BREAK         = 6;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * @param int    $type
     * @param string $content
     */
    public function __construct($type, $content)
    {
        $this->type    = $type;
        $this->content = $content;
    }

    /**
     * @param int $type
     *
     * @return bool
     */
    public function is($type)
    {
        if (self::ENCLOSURE_BOUNDARY === $this->type && (self::ENCLOSURE_START === $type || self::ENCLOSURE_END === $type)) {
            return true;
        }

        return $type === $this->type;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
