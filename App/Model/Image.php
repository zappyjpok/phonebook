<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 11/11/2015
 * Time: 10:56 AM
 */

class Image {


    /**
     * Get all images from the database
     *
     * @param $id
     * @return mixed
     */
    public static function All($id)
    {
        try{
            $sql = "SELECT *
                    FROM tblImage
                    WHERE imgContactID = :id";
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
     * Add an image to the database
     *
     * @param $id
     * @param $path
     * @param bool $main
     * @return string
     */
    public static function Add($id, $path, $main = false)
    {
        try{
            $sql = 'INSERT INTO tblImage (imgContactID, imgPath, imgMain)
                    VALUES (:id, :path, :main)';
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':id', $id);
            $db->bind(':path', $path);
            $db->bind(':main', $main);
            $db->execute();
            $errors = $db->getErrors();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    /**
     * Add the main image
     *
     * @param $contactID
     * @param $imageID
     */
    public static function AddMain($contactID, $imageID)
    {
        try {
            $sql = 'UPDATE tblImage
                    SET imgMain = FALSE
                    WHERE imgContactID = :contactID';
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':contactID', $contactID);
            $db->execute();
            $errors = $db->getErrors();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        try {
            $sql = 'UPDATE tblImage
                    SET imgMain = TRUE
                    WHERE imgImageID = :imageID';
            $db = new PDO_Connect();
            $db->prepare($sql);
            $db->bind(':imageID', $imageID);
            $db->execute();
            $errors2 = $db->getErrors();
        } catch (Exception $e) {
            $error2 = $e->getMessage();
        }
    }

    public static function getMain($id)
    {
        try{
            $sql = "SELECT imgPath
                    FROM tblImage
                    WHERE imgContactID = :id
                    AND imgMain = 1
                    LIMIT 1";
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
}