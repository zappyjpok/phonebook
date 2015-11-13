<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 11/11/2015
 * Time: 10:56 AM
 */

class Image {

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
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}