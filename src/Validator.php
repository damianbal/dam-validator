<?php

/**
 * dam-validator
 * Very basic input validator
 * @author Damian Balandowski (damianbal)
 */

namespace damianbal\DamValidator;

class Validator
{
    protected $invalid_inputs = [];

    /**
     * Returns invalid inputs
     *
     * @return array
     */
    public function getInvalidInputs()
    {
        return $this->invalid_inputs;
    }

    /**
     * Validate rule
     *
     * @param mixed $value
     * @param string $rule_key
     * @param mixed $rule_val
     * @return boolean
     */
    public static function validateRule($value, $rule_key, $rule_val, $other_inputs = []) : bool
    {
        $str_len = strlen($value);
        $rule_val_int = intval($rule_val);

        if($rule_key == 'min')
        {
            if($str_len <= $rule_val_int) return false;
        }

        if($rule_key == 'max')
        {
            if($str_len >= $rule_val_int) return false;
        }

        if($rule_key == 'same')
        {
            if(!empty($other_inputs))
            {
                if($value != $other_inputs[$rule_val]) return false;
            }
        }

        if($rule_key == 'required')
        {
            if($str_len < 1) return false;
        }

        if($rule_key == 'numeric')
        {
            return is_numeric($value);
        }

        if($rule_key == 'boolean' || $rule_key == 'bool')
        {
            return is_bool($value);
        }

        if($rule_key == 'array')
        {
            return is_array($value);
        }

        if($rule_key == 'email')
        {
            return filter_var($value, FILTER_VALIDATE_EMAIL);
        }

        if($rule_key == 'url')
        {
            return filter_var($value, FILTER_VALIDATE_URL);
        }
        
        return true;
    }

    /**
     * Validate without getting information what inputs are invalid
     *
     * @param array $inputs
     * @param array $rules
     * @return boolean
     */
    public static function quickValidate(array $inputs, $rules = []) : bool 
    {
        // go trough all the rules, example: min:3|max:6|required
        foreach($rules as $key => $value)
        {
            $rules_separated = explode('|', $value);

            // input name, example: username
            $input = $inputs[$key]; 

            // go trough each rule, example: min:3
            foreach($rules_separated as $rule) {
                $r = explode(':', $rule);

                $rule_key = $r[0];
                $rule_value = @$r[1];

                if(Validator::validateRule($input, $rule_key, $rule_value, $inputs) == false) return false;
            }
        }

        return true;
    }

    /**
     * Validate inputs
     *
     * @param array $inputs
     * @param array $rules
     * @return boolean
     */
    public function validate(array $inputs, $rules = []) : bool 
    {
        $this->invalid_inputs = []; // reset invalid inputs

        $valid = true;
        // go trough all the rules, example: min:3|max:6|required
        foreach($rules as $key => $value)
        {
            $rules_separated = explode('|', $value);

            // input name, example: username
            $input = $inputs[$key]; 

            // go trough each rule, example: min:3
            foreach($rules_separated as $rule) {
                $r = explode(':', $rule);

                $rule_key = $r[0];
                $rule_value = @$r[1];

                if(Validator::validateRule($input, $rule_key, $rule_value, $inputs) == false) {
                    $valid = false;                            // this validate will fail 
                    $this->invalid_inputs[$input] = $rule_key; // set which input is invalid and which rule
                 }
            }
        }

        return $valid;
    }
}