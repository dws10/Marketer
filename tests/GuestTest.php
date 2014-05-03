<?php

namespace dws10\Marketer;

class GuestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers            \SebastianBergmann\Money\Currency::__construct
     * @expectedException \SebastianBergmann\Money\InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidConstructorArgument()
    {
        new Guest('guest');
    }

    /**
     * @covers \dws10\Marketer\class\Guest::__construct
     */
    public function testObjectCanBeConstructedForValidConstructorArgument()
    {
        $g = new Guest();

        $this->assertInstanceOf('dws10\\Marketer\\class\\Guest', $g);

        return $g;
    }

}
