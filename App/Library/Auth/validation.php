<?php
/**
 * Created by PhpStorm.
 * User: Shawn Legge
 * Date: 30/10/2015
 * Time: 7:07 PM
 */

require_once('../App/Model/User.php');

class validation {


    /**
     * @var array -- The array of validation errors
     */
    protected $errors = [];

    public function __construct()
    {

    }

    /**
     * Validates the values passed into it
     *
     * @para value -- the value being evaluated
     * @para rules -- the rules and messages to pass into it
     * Rule 1 : empty -- value can't be empty
     * Rule 2 : validEmail -- value must be a valid email
     * Rule 3: name -- value must be a valid name
     * Rule 4: emailAvailable -- Email is already taken
     * Rule 5: usernameAvailable -- Username is already taken
     *
     * @return array
     */
    public function validate($value, $rules, $match = null)
    {
        if(!empty($rules))
        {
            foreach($rules as $key => $rule)
            {
                // Switch statement that goes through each rule
                switch($key)
                {
                    case 'empty':
                        if($this->checkEmpty($value, $rule) !== true) { return; }
                        break;
                    case 'name':
                        //$this->errors [] = 'Check if name';
                        break;
                    case 'validEmail' :
                        if($this->checkEmail($value, $rule) !== true) { return; }
                        break;
                    case 'emailAvailable' :
                        if($this->checkEmailAvailable($value, $rule) !== true) { return; }
                        break;
                    case 'usernameAvailable' :
                        if ($this->checkUsernameAvailable($value, $rule) !== true) { return; };
                        break;
                    case 'match' :
                        if ($this->checkMatch($value, $rule, $match) !== true) { return; }
                        break;
                }
            }
        } else {
            $this->errors [] = 'Rules are empty';
        }
        return $this->errors;
    }

    /**
     * @return array -- array of all the error messages
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Check if empty
     *
     * @param $value
     * @param $message
     * @return bool
     */
    private function checkEmpty($value, $message)
    {

        if(trim($value) == '' || empty($value))
        {
            $this->errors [] = $message;
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check if an email address
     *
     * @param $value
     * @param $message
     * @return bool
     */
    private function checkEmail($value, $message)
    {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors [] = $message;
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check if Email is available
     *
     * @param $value
     * @param $message
     * @return bool
     */
    private function checkEmailAvailable($value, $message)
    {
        $user = User::find_email_exists(strtolower($value));

        if($user !== false)
        {
            $this->errors [] = $message;
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check if username is available
     *
     * @param $value
     * @param $message
     * @return bool
     */
    private function checkUsernameAvailable($value, $message)
    {
        $user = User::find_username_exists(strtolower($value));

        if($user !== false)
        {
            $this->errors [] = $message;
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check if the password matches
     *
     * @param $value
     * @param $message
     * @param $match
     * @return bool
     */
    private function checkMatch($value, $message, $match)
    {
        if($value !== $match)
        {
            $this->errors [] = $message;
            return false;
        } else {
            return true;
        }
    }
}