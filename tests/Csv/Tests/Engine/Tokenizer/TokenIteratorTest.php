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
class TokenIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getIteratorTestData
     */
    public function testIterator($content, array $tokens)
    {
        $reader = new Reader();
        $reader->open(fopen('data://text/plain;base64,'.base64_encode($content), 'r'));
        $iterator = new TokenIterator(new Tokenizer(',', new Enclosure('~', '|')), $reader);

        $index = -1;
        foreach ($iterator as $index => $token) {
            $this->assertTrue($token->is($tokens[$index][0]));
            $this->assertEquals($tokens[$index][1], $token->getContent());
        }

        $this->assertEquals(count($tokens), $index + 1);
    }

    public function getIteratorTestData()
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
        );
    }
}
