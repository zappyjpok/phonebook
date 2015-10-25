<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 30/08/2015
 * Time: 5:32 PM
 */


require_once('../App/Database/PDO_Connect.php');

class User {

    public $FirstName;
    public $LastName;
    public $Email;
    public $Id;

    public static function All()
    {
        try{
            $sql = "SELECT useUserId, useFirstName, useLastName, useEmail FROM tblUser";
            $db = new PDO_Connect();
            $db->query($sql);
            $errors = $db->getErrors();
            $results = $db->result_set();

        } catch(Exception $e) {
            $error = $e->getMessage();
        }

        return $results;
    }

    public static function Add($firstName, $lastName, $email, $password)
    {
        try {
            $pass = password_hash($password, PASSWORD_DEFAULT);
            $sql = 'INSERT INTO tblUser(useFirstName, useLastName, useEmail, usePassword)
                    VALUES (:FirstName, :LastName, :Email, :Password)';
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':FirstName', $firstName);
            $db->bind(':LastName', $lastName);
            $db->bind(':Email', $email);
            $db->bind(':Password', $pass);
            $db->execute();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    public static function find_by_email($email)
    {
        try {
            $sql = "SELECT useUserId, usePassword, useEmail
            FROM tblUser
            WHERE useEmail=:Email";
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':Email', $email);
            $errors = $db->getErrors();
            $results = $db->single();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return $results;
    }

    public static function find($id)
    {
        try {
            $sql = "SELECT useUserId, useFirstName, useLastName, useEmail
                FROM tblUser
                WHERE useUserID=:Id";
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':Id', $id, PDO::PARAM_INT);
            $results = $db->single();
            $errors = $db->getErrors();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return $results;
    }

    public static function authenticate($email, $password)
    {
        $user = User::find_by_email($email);
        $count = count($user);

        //check if user if found
        if($count > 0) {
            // check password
            if(password_verify($password, $user['usePassword']))
            {
                // password matches
                return User::find($user['useUserId']);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}