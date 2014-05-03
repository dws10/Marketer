<?php

require_once('var/lib/hudson/jobs/marketer/marketer_new/workspace/src/class/Validator.php';

class ValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers test construct
     */
    public function testObjectCanBeConstructedForValidConstructorArgument()
    {
        $validator = new Validation();

        $this->assertInstanceOf('Validation', $validator);

        return $validator;
    }

}
