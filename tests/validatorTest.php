<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use damianbal\DamValidator\Validator;

final class ValidatorTest extends TestCase
{
    // test validateRule
    public function testValidatorValidateRule()
    {
        $this->assertEquals(Validator::validateRule('damian', 'min', '1'), true);
        $this->assertEquals(Validator::validateRule('damian', 'max', '10'), true);
        $this->assertEquals(Validator::validateRule('', 'required', null), false);
        $this->assertEquals(Validator::validateRule('1','numeric',null), true);
        $this->assertEquals(Validator::validateRule('55', 'numeric',null), true);
        $this->assertEquals(Validator::validateRule('ok', 'numeric',null), false);
        $this->assertEquals(Validator::validateRule(true, 'bool',null), true);
        $this->assertEquals(Validator::validateRule('dfsdfsd', 'email',null), false);
        $this->assertEquals(Validator::validateRule('ddf@fddf.com', 'email',null), true);
        $this->assertEquals(Validator::validateRule('http://codesnipz.com/', 'url', null), true);
        $this->assertEquals(Validator::validateRule('something', 'url', null), false);


    }

    // test validator
    public function testValidator()
    {
        $inputs = [];
        $inputs['name'] = 'Damian';
        $inputs['name_repeat'] = 'Damian';

        $validator = new Validator();

        // valid so it should be true
        $validation = $validator->validate($inputs, [
            'name' => 'required|min:3|max:10',
            'name_repeat' => 'required|min:3|max:10'
        ]);

        // not valid so it should be false
        $validation1 = $validator->validate($inputs, [
            'name' => 'max:3'
        ]);

        $validation2 = $validator->validate($inputs, [
            'name_repeat' => 'same:name'
        ]);

        $this->assertEquals($validation, true);
        $this->assertEquals($validation1, false);
        $this->assertEquals($validation2, true);
    }
}