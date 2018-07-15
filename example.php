<?php

include 'vendor/autoload.php';

use damianbal\DamValidator\Validator;

$inputs = [
    'name' => 'Damian',
    'age' => '23',
    'repeat_name' => 'Damian'
];

// this will be valid
if(Validator::quickValidate($inputs, 
[
    'name' => 'required|min:3',
    'age' => 'required|numeric',
    'repeat_name' => 'same:name'
])) {
    echo "It is good!";
}
else {
    echo "It is not good!";
}

// this will be invalid
$inputs2 = [
    'name' => 'Das'
];

$validator = new Validator();

if(!$validator->validate($inputs2, ['name' => 'min:4'])) {
    echo "<br> Invalid inputs: ";
    var_dump($validator->getInvalidInputs());
}