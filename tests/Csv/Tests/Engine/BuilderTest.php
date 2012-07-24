<?php

namespace Csv\Tests\Engine;

use Csv\Engine\Builder;
use Csv\Engine\Enclosure;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class BuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getTestBuildData
     */
    public function testBuild($result, array $values, $forceEnclosure)
    {
        $builder = new Builder(',', new Enclosure('~', '|'));
        $this->assertEquals($result, $builder->build($values, $forceEnclosure));
    }

    public function getTestBuildData()
    {
        return array(
            array('a,b,c',           array('a', 'b', 'c'), false),
            array('~a~,~b~,~c~',     array('a', 'b', 'c'), true),
            array('~a,b~,c',         array('a,b', 'c'),    false),
            array('~|~a|~~,~|~b|~~', array('~a~', '~b~'),  true),
            array('~|~a|~~,~|~b|~~', array('~a~', '~b~'),  false),
        );
    }
}
