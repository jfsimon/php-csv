<?php

namespace Csv\Tests\Resource\Stream;

use Csv\Resource\Stream\Buffer;
use Csv\Resource\ReaderInterface;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class BufferTest extends \PHPUnit_Framework_TestCase
{
    public function testAppend()
    {
        $buffer = new Buffer();
        $buffer->append('test');

        $this->assertEquals('test', $buffer->getContent());
    }

    public function testClear()
    {
        $buffer = new Buffer();
        $buffer->append('test')->clear();

        $this->assertEquals('', $buffer->getContent());
    }

    public function testIsEmpty()
    {
        $buffer = new Buffer();
        $buffer->append('test');

        $this->assertFalse($buffer->isEmpty());

        $buffer->clear();

        $this->assertTrue($buffer->isEmpty());
    }

    /**
     * @dataProvider getHasLineBreakData
     */
    public function testHasLineBreak($lineBreak, $content, $hasLineBreak)
    {
        $buffer = new Buffer($lineBreak);
        $buffer->append($content);

        $this->assertEquals($hasLineBreak, $buffer->hasLineBreak());
    }

    /**
     * @dataProvider getReadUntilLineBreakData
     */
    public function testReadUntilLineBreak($content, array $lines, $remain)
    {
        $buffer = new Buffer();
        $buffer->append($content);

        foreach ($lines as $line) {
            $this->assertEquals($line, $buffer->readUntilLineBreak());
        }

        $this->assertEquals($remain, $buffer->getContent());
    }

    public function getHasLineBreakData()
    {
        return array(
            array(ReaderInterface::LB_WINDOWS, "a\r\nb", true),
            array(ReaderInterface::LB_WINDOWS, "a\nb",   false),
            array(ReaderInterface::LB_WINDOWS, "a\rb",   false),
            array(ReaderInterface::LB_WINDOWS, "ab",     false),
            array(ReaderInterface::LB_MAC,     "a\r\nb", true),
            array(ReaderInterface::LB_MAC,     "a\nb",   false),
            array(ReaderInterface::LB_MAC,     "a\rb",   true),
            array(ReaderInterface::LB_MAC,     "ab",     false),
            array(ReaderInterface::LB_UNIX,    "a\r\nb", true),
            array(ReaderInterface::LB_UNIX,    "a\nb",   true),
            array(ReaderInterface::LB_UNIX,    "a\rb",   false),
            array(ReaderInterface::LB_UNIX,    "ab",     false),
            array(ReaderInterface::LB_AUTO,    "a\r\nb", true),
            array(ReaderInterface::LB_AUTO,    "a\nb",   true),
            array(ReaderInterface::LB_AUTO,    "a\rb",   true),
            array(ReaderInterface::LB_AUTO,    "ab",     false),
        );
    }

    public function getReadUntilLineBreakData()
    {
        return array(
            array("line1",               array(),                 'line1'),
            array("line1\nline2",        array('line1'),          'line2'),
            array("line1\nline2\nline3", array('line1', 'line2'), 'line3'),
        );
    }
}
