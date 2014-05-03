<?php

require_once('../src/class/Guest.php');

class GuestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers            test constructor
     * @expectedException InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidConstructorArgument()
    {
        new Guest('guest');
    }

    /**
     * @covers test construct
     */
    public function testObjectCanBeConstructedForValidConstructorArgument()
    {
        $g = new Guest();

        $this->assertInstanceOf(new Guest, $g);

        return $g;
    }

}
