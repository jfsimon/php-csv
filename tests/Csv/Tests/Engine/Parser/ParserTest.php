<?php

namespace Csv\Tests\Engine;

use Csv\Engine\Tokenizer\Tokenizer;
use Csv\Engine\Tokenizer\Token;
use Csv\Engine\Tokenizer\TokenIterator;
use Csv\Engine\Parser\Parser;
use Csv\Engine\Parser\State;
use Csv\Engine\Enclosure;
use Csv\Resource\Stream\Reader;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getParseTestData
     */
    public function testParse($content, array $result)
    {
        $reader = new Reader();
        $reader->open(fopen('data://text/plain;base64,'.base64_encode($content), 'r'));
        $parser = new Parser(new TokenIterator(new Tokenizer(',', new Enclosure('~', '|')), $reader));

        $rowIndex = -1;
        foreach ($result as $rowIndex => $values) {
            $found = $parser->parse(new State());

            foreach ($values as $cellIndex => $value) {
                $this->assertEquals($value, $found[$cellIndex]);
            }

            $this->assertEquals(count($values), count($found));
        }

        $this->assertEquals(count($result), $rowIndex + 1);
    }

    public function getParseTestData()
    {
        return array(
            array(
                "a,b,c\nd,e,f",
                array(
                    array('a', 'b', 'c'),
                    array('d', 'e', 'f'),
                )
            ),
        );
    }
}
