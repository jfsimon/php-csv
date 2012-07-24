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
     * @var Enclosure|null
     */
    private $enclosure;

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
                $types[$enclosure->escape.$enclosure->start] = Token::ENCLOSURE_ESCAPED_BOUNDARY;

                if ($enclosure->start === $enclosure->escape) {
                    $types[str_repeat($enclosure->start, 3)] = Token::ENCLOSURE_TRIPLE_BOUNDARY;
                }
            } else {
                $types[$enclosure->start] = Token::ENCLOSURE_START;
                $types[$enclosure->escape.$enclosure->start] = Token::ENCLOSURE_ESCAPED_START;

                $types[$enclosure->end] = Token::ENCLOSURE_END;
                $types[$enclosure->escape.$enclosure->end] = Token::ENCLOSURE_ESCAPED_END;

                if ($enclosure->start === $enclosure->escape) {
                    $types[str_repeat($enclosure->start, 3)] = Token::ENCLOSURE_TRIPLE_START;
                }

                if ($enclosure->end === $enclosure->escape) {
                    $types[str_repeat($enclosure->end, 3)] = Token::ENCLOSURE_TRIPLE_END;
                }
            }
        }

        $this->matcher   = new Matcher($types);
        $this->enclosure = $enclosure;
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

    /**
     * @return Enclosure|null
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }
}
