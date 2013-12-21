<?php

namespace sJoTest\Libraries;

use sJo\Libraries\Arr;

class ArrTest extends \PHPUnit_Framework_TestCase
{
    public function testExtend()
    {
        $response = Arr::extend(array(
            'lvl1.1' => 1,
            'lvl1.2' => 2,
            'lvl1.3' => array(
                1, 2, 3
            ),
            'obj' => (object) array(
                'lvl2a' => 0,
                'lvl2b' => array(
                    'lvl3.1' => 1
                )
            )
        ), array(
            'lvl1.1' => 1,
            'lvl1.2' => 0,
            'lvl1.3' => array(
                4, 5, 6
            ),
            'lvl1.4' => 3,
            'obj' => (object) array(
                'lvl2a' => 1,
                'lvl2b' => array(
                    'lvl3.2' => 2
                )
            )
        ));

        $this->assertEquals($response['lvl1.1'], 1);
        $this->assertEquals($response['lvl1.2'], 0);
        $this->assertEquals($response['lvl1.3'][0], 4);
        $this->assertEquals($response['lvl1.3'][1], 5);
        $this->assertEquals($response['lvl1.3'][2], 6);
        $this->assertEquals($response['lvl1.4'], 3);
        $this->assertEquals($response['obj']->lvl2a, 1);
        $this->assertEquals($response['obj']->lvl2b['lvl3.2'], 2);
    }
}
