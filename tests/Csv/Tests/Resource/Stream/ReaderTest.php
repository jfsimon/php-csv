<?php

namespace Csv\Tests\Resource\Stream;

use Csv\Exception\ReadingFinishedException;
use Csv\Resource\Stream\Reader;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getReadLineData
     */
    public function testReadLine($content, array $lines)
    {
        $resource = fopen('data://text/plain;base64,'.base64_encode($content), 'r');

        $reader = new Reader(1);
        $reader->open($resource);

        foreach ($lines as $line) {
            $this->assertEquals($line, $reader->readLine(true));
        }

        try {
            $reader->readLine();
            $this->assertTrue(false);
        } catch (ReadingFinishedException $e) {
            $this->assertTrue(true);
        }
    }

    public function getReadLineData()
    {
        return array(
            array("line1",               array('line1')),
            array("line1\nline2",        array('line1', 'line2')),
            array("line1\nline2\nline3", array('line1', 'line2', 'line3')),
            array("\n\n",                array('', '', '')),
            array("한국말\n조선말",        array('한국말', '조선말')),
            array("漢字\n汉字",           array('漢字', '汉字')),
            array("a\nb\rc\r\nd",        array('a', 'b', 'c', 'd')),
            array("a\nb\rc\r\nd",        array('a', 'b', 'c', 'd')),
        );
    }
}
