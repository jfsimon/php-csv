<?php

namespace Csv\Tests\Engine\Tokenizer;

use Csv\Engine\Tokenizer\Matcher;
use Csv\Engine\Tokenizer\Tokenizer;
use Csv\Engine\Tokenizer\Token;
use Csv\Engine\Tokenizer\TokenIterator;
use Csv\Engine\Enclosure;
use Csv\Resource\Stream\Reader;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getTokenizeTestData
     */
    public function testTokenize($content, array $tokens)
    {
        $tokenizer = new Tokenizer(',', new Enclosure('~', '|'));

        $index = -1;
        foreach ($tokenizer->tokenize($content) as $index => $token) {
            $this->assertTrue($token->is($tokens[$index][0]));
            $this->assertEquals($tokens[$index][1], $token->getContent());
        }

        $this->assertEquals(count($tokens), $index + 1);
    }

    public function testTripleCase()
    {
        $tokenizer = new Tokenizer(',', new Enclosure('|', '|'));
        $tokens = $tokenizer->tokenize('|-||-|||');

        $this->assertTrue($tokens[0]->is(Token::ENCLOSURE_BOUNDARY));
        $this->assertTrue($tokens[2]->is(Token::ENCLOSURE_ESCAPED_BOUNDARY));
        $this->assertTrue($tokens[4]->is(Token::ENCLOSURE_TRIPLE_BOUNDARY));
    }

    public function getTokenizeTestData()
    {
        return array(
            array(
                'a,b,c',
                array(
                    array(Token::CONTENT,   'a'),
                    array(Token::SEPARATOR, ','),
                    array(Token::CONTENT,   'b'),
                    array(Token::SEPARATOR, ','),
                    array(Token::CONTENT,   'c'),
                )
            ),
            array(
                '~a~,~|~b|~~',
                array(
                    array(Token::ENCLOSURE_BOUNDARY,         '~'),
                    array(Token::CONTENT,                    'a'),
                    array(Token::ENCLOSURE_BOUNDARY,         '~'),
                    array(Token::SEPARATOR,                  ','),
                    array(Token::ENCLOSURE_BOUNDARY,         '~'),
                    array(Token::ENCLOSURE_ESCAPED_BOUNDARY, '|~'),
                    array(Token::CONTENT,                    'b'),
                    array(Token::ENCLOSURE_ESCAPED_BOUNDARY, '|~'),
                    array(Token::ENCLOSURE_BOUNDARY,         '~'),
                )
            ),
            array(
                "a\nb\rc\r\nd",
                array(
                    array(Token::CONTENT,    'a'),
                    array(Token::LINE_BREAK, "\n"),
                    array(Token::CONTENT,    'b'),
                    array(Token::LINE_BREAK, "\r"),
                    array(Token::CONTENT,    'c'),
                    array(Token::LINE_BREAK, "\r\n"),
                    array(Token::CONTENT,    'd'),
                )
            ),
        );
    }
}
