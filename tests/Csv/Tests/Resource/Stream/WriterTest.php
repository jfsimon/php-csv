<?php

namespace Csv\Tests\Resource\Stream;

use Csv\Resource\Stream\Writer;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class WriterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getWriteLineData
     */
    public function testWriteLine($content, array $lines)
    {
        $fileName = sys_get_temp_dir().DIRECTORY_SEPARATOR.'csv_tests'.DIRECTORY_SEPARATOR.'write.test';
        @mkdir(dirname($fileName), 0777, true);
        @touch($fileName);

        $writer = new Writer(Writer::LB_UNIX);
        $writer->open(fopen($fileName, 'w'));

        foreach ($lines as $line) {
            $writer->writeLine($line);
        }

        $writer->close();

        $this->assertEquals($content, file_get_contents($fileName));

        @unlink($fileName);
    }

    public function getWriteLineData()
    {
        return array(
            array("line1\n",               array('line1')),
            array("line1\nline2\n",        array('line1', 'line2')),
            array("line1\nline2\nline3\n", array('line1', 'line2', 'line3')),
            array("\n\n\n",                array('', '', '')),
            array("한국말\n조선말\n",        array('한국말', '조선말')),
            array("漢字\n汉字\n",           array('漢字', '汉字')),
        );
    }
}
