<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 8/11/2015
 * Time: 12:46 PM
 */

require_once('../App/Database/PDO_Connect.php');
require_once('../App/Model/Image.php');

class Contact {

    public static function checkContactUser($userID, $contactID)
    {
        $contact = Self::find($contactID);

        if((int)$contact['conUserID'] === (int)$userID)
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Finds all contacts of a particular ID
     *
     * @param $id
     * @return mixed
     */
    public static function All($id)
    {
        try{
            $sql = "SELECT tblContact.conContactID, tblContact.conUserID, tblContact.conFirstName, tblContact.conLastName,
                    tblContact.conPhone, tblContact.conEmail, tblImage.imgPath, tblImage.imgMain
                    FROM tblContact
                    LEFT JOIN tblImage
                    ON tblContact.conContactID = tblImage.imgContactID
                    WHERE conUserID = :id
                    AND imgMain = TRUE";
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

    /**
     * Delete a contact
     *
     * @param $id
     */
    public static function delete($id)
    {
        try{
            $sql = 'DELETE FROM tblContact
                    WHERE conContactID = :ID';
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':ID', $id);
            $db->execute();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    /**
     * edit a contact
     *
     * @param $conteactID
     * @param $firstName
     * @param $lastName
     * @param $username
     * @param $email
     */
    public static function edit($contactID, $firstName, $lastName, $phone, $email)
    {
        try {
            $sql = 'UPDATE tblContact
                    SET conFirstName = :FirstName,
                    conLastName = :LastName,
                    conPhone = :Phone,
                    conEmail = :Email
                    WHERE conContactID = :ID';
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':ID', $contactID);
            $db->bind(':FirstName', $firstName);
            $db->bind(':LastName', $lastName);
            $db->bind(':Phone', $phone);
            $db->bind(':Email', $email);
            $db->execute();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return $error;
    }

    /**
     * Find the user's contact information
     *
     * @param $id
     * @return mixed
     */
    public static function find($id)
    {
        try {
            $sql = "SELECT *
                    FROM tblContact
                    WHERE conContactID = :ID";
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':ID', $id);
            $results = $db->single();
            $errors = $db->getErrors();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return $results;
    }

    /**
     * Find contact with email
     *
     * @param $id
     * @return array
     */
    public static function findAll($id)
    {
        $contact = self::find($id);
        $images = Image::All($id);
        $result = [];
        $i = 0;

        $result['ID'] = $contact['conContactID'];
        $result['firstName'] = $contact['conFirstName'];
        $result['lastName'] = $contact['conLastName'];
        $result['email'] = $contact['conEmail'];
        $result['phone'] = $contact['conPhone'];

            foreach($images as $image)
            {
                $result['images'][$i] ['ID'] = $image['imgImageID'];
                $result['images'][$i] ['ContactID'] = $image['imgContactID'];
                $result['images'][$i] ['Path'] = $image['imgPath'];
                $result['images'][$i] ['Main'] = $image['imgMain'];
                $result['images'][$i] ['Main'] = $image['imgMain'];
                $i ++;
            }


        return $result;
    }

    public static function search($search)
    {
        $search = "%$search%";
        try {
            $sql = "SELECT DISTINCT tblContact.conContactID, tblContact.conUserID, tblContact.conFirstName, tblContact.conLastName,
                    tblContact.conPhone, tblContact.conEmail, tblImage.imgPath, tblImage.imgMain
                    FROM tblContact
                    LEFT JOIN tblImage
                    ON tblContact.conContactID = tblImage.imgContactID
                    WHERE  conFirstName LIKE :search
                    OR conLastName LIKE :search
                    GROUP BY conContactID";
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':search', $search);
            $results = $db->result_set();
            $errors = $db->getErrors();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return $results;
    }

}