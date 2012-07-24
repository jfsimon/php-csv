<?php

namespace Csv\Engine\Tokenizer;

use Csv\Engine\Enclosure;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Tokenizer
{
    /**
     * @var Matcher
     */
    private $matcher;

    /**
     * Constructor.
     *
     * @param string         $separator
     * @param Enclosure|null $enclosure
     */
    public function __construct($separator, Enclosure $enclosure = null)
    {
        $types = array(
            "\r\n"     => Token::LINE_BREAK,
            "\r"       => Token::LINE_BREAK,
            "\n"       => Token::LINE_BREAK,
            $separator => Token::SEPARATOR,
        );

        if (null !== $enclosure) {
            if ($enclosure->start === $enclosure->end) {
                $types[$enclosure->start] = Token::ENCLOSURE_BOUNDARY;
            } else {
                $types[$enclosure->start] = Token::ENCLOSURE_START;
                $types[$enclosure->end] = Token::ENCLOSURE_END;
            }
            $types[$enclosure->escape] = Token::ENCLOSURE_ESCAPE;
        }

        $this->matcher = new Matcher($types);
    }

    /**
     * Tokenizes some content.
     *
     * @param string $string Content to tokenize
     *
     * @return array An array of tokens
     */
    public function tokenize($string)
    {
        $tokens = array();
        $start  = 0;
        $length = strlen($string);

        $this->matcher->build($string);

        while ($length > $start) {
            $match = $this->matcher->fetch();

            if (null === $match) {
                $tokens[] = new Token(Token::CONTENT, substr($string, $start));

                return $tokens;
            }

            if ($match['position'] > $start) {
                $tokens[] = new Token(Token::CONTENT, substr($string, $start, $match['position'] - $start));
                $start = $match['position'];
            }

            $tokens[] = new Token($match['type'], $match['content']);
            $start += $match['length'];
        }

        return $tokens;
    }
}
