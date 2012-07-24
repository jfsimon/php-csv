<?php

namespace Csv\Tests\Engine;

use Csv\Engine\Builder;
use Csv\Engine\Enclosure;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class EnclosureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getTestEnclosureData
     */
    public function testEnclosure($disclosedValue, $enclosedValue)
    {
        $enclosure = new Enclosure('~', '|');

        $this->assertEquals($enclosedValue,  $enclosure->enclose($disclosedValue, array(',')));
        $this->assertEquals($disclosedValue, $enclosure->disclose($enclosedValue));
    }

    public function getTestEnclosureData()
    {
        return array(
            array('abc',   'abc'),
            array('a,b,c', '~a,b,c~'),
            array('a~b~c', '~a|~b|~c~'),
        );
    }
}
