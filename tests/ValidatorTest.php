<?php

require_once('../src/class/Validator.php');

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers            test constructor
     * @expectedException InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidConstructorArgument()
    {
        new Validation('guest');
    }

    /**
     * @covers test construct
     */
    public function testObjectCanBeConstructedForValidConstructorArgument()
    {
        $validator = new Validation();

        $this->assertInstanceOf(new Validation, $validator);

        return $validator;
    }

}
