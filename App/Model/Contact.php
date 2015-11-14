<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 8/11/2015
 * Time: 12:46 PM
 */

require_once('../App/Database/PDO_Connect.php');

class Contact {

    /**
     * Finds all contacts of a particular ID
     *
     * @param $id
     * @return mixed
     */
    public static function All($id)
    {
        try{
            $sql = "SELECT *
                    FROM tblContact
                    WHERE conUserID = :id";
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':id', $id);
            $errors = $db->getErrors();
            $results = $db->result_set();

        } catch(Exception $e) {
            $error = $e->getMessage();
        }

        return $results;
    }

    /**
     * Adds a contact to the database
     *
     * @param $id
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $phone
     * @return string
     */
    public static function Add($id, $firstName, $lastName, $email, $phone)
    {
        try{
            $sql = 'INSERT INTO tblContact (conUserID, conFirstName, conLastName, conEmail, conPhone)
                    VALUES (:id, :FirstName, :LastName, :Email, :Phone)';
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':id', $id);
            $db->bind(':FirstName', $firstName);
            $db->bind(':LastName', $lastName);
            $db->bind(':Email', $email);
            $db->bind(':Phone', $phone);
            $db->execute();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return $db->lastInsertId();
    }



}