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
class MatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getMatcherData
     */
    public function testMatcher(array $types, $content, array $expected)
    {
        $matcher = new Matcher($types);
        $matcher->build($content);

        $index = 0;
        while (null !== $match = $matcher->fetch()) {
            $this->assertTrue(isset($expected[$match['position']]));
            $this->assertEquals($expected[$match['position']], $match['content']);
            $this->assertEquals($expected[$match['position']], $match['type']);
            $index ++;
        }

        $this->assertEquals(count($expected), $index);
    }

    public function getMatcherData()
    {
        $types = array('a' => 'a', 'b' => 'b', 'c' => 'c', 'ab' => 'ab', 'bc' => 'bc');

        return array(
            array($types, '-a-b-c-', array(1 => 'a', 3 => 'b', 5 => 'c')),
            array($types, '-ab-c-', array(1 => 'ab', 4 => 'c')),
            array($types, '-a-bc-', array(1 => 'a', 3 => 'bc')),
        );
    }
}
