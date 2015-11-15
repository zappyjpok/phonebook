<?php
/**
 * Created by PhpStorm.
 * user: thuyshawn
 * Date: 30/08/2015
 * Time: 5:32 PM
 */

require_once('../App/Database/PDO_Connect.php');

class User {

    public $FirstName;
    public $LastName;
    public $Email;
    public $Id;

    /**
     * Get all users
     *
     * @return mixed
     */
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

    public static function Add($firstName, $lastName, $username, $email, $password)
    {
        try {
            $pass = password_hash($password, PASSWORD_DEFAULT);
            $sql = 'INSERT INTO tblUser(useFirstName, useLastName, useUsername, useEmail, usePassword)
                    VALUES (:FirstName, :LastName, :Username, :Email, :Password)';
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':FirstName', $firstName);
            $db->bind(':LastName', $lastName);
            $db->bind(':Username', $username);
            $db->bind(':Email', $email);
            $db->bind(':Password', $pass);
            $db->execute();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    public static function edit($userID, $firstName, $lastName, $username, $email)
    {
        try {
            $sql = 'UPDATE tblUser
                    SET useFirstName = :FirstName,
                    useLastName = :LastName,
                    useUsername = :Username,
                    useEmail = :Email
                    WHERE useUserID = :ID';
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':ID', $userID);
            $db->bind(':FirstName', $firstName);
            $db->bind(':LastName', $lastName);
            $db->bind(':Username', $username);
            $db->bind(':Email', $email);
            $db->execute();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    public static function editPassword($id, $password)
    {
        $pass = password_hash($password, PASSWORD_DEFAULT);
        try {
            $sql = 'UPDATE tblUser
                    SET usePassword = :password,
                    WHERE useUserID = :ID';
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':ID', $id);
            $db->bind(':FirstName', $pass);
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

    /**
     * Check if email exists
     *
     * @param $email
     * @return mixed
     */
    public static function find_email_exists($email)
    {
        try {
            $sql = "SELECT useUserId
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

    /**
     * Check if a username exists
     *
     * @param $username
     * @return mixed
     */
    public static function find_username_exists($username)
    {
        try {
            $sql = "SELECT useUserId
            FROM tblUser
            WHERE useUserName=:username";
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':username', $username);
            $errors = $db->getErrors();
            $results = $db->single();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return $results;
    }

    /**
     * Find a User
     *
     * @param $id
     * @return mixed
     */
    public static function find($id)
    {
        try {
            $sql = "SELECT useUserId, useFirstName, useLastName, useEmail, useUserName
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

    /**
     * Check if the user's password and email match
     *
     * @param $email
     * @param $password
     * @return bool
     */
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