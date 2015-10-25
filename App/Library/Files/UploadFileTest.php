<?php
/**
 * Created by PhpStorm.
 * User: thuyshawn
 * Date: 19/06/2015
 * Time: 11:11 PM
 */

namespace App\library;


class PostTest {

    private $file;
    private $name;
    private $messages = [];

    public function __construct($file, $name) {
        $this->file = $file;
        $this->name = $name;
    }

    public function runTest() {
        $this->checkFileExists();

        return $this->message;
    }

    private function checkFileExists(){

        if(file_exists($this->file)){
            $this->message[] = 'The file exists';
        } else {
            $this->message[] = 'The file does not exist';
        }
    }

    private function checkFileWritable($file){

        if(is_writable($this->file)){
            $this->message[] = 'The file is writable';
        } else {
            $this->message[] = 'The file does not writable';
        }
    }
    private function checkFilePost(){
        if (!empty($this->file)) {
            $this->message[] = $this->file["$this->name"]['name'] . 'was uploaded during the submit';
        } else {
            $this->message[] = "There Files post was empty";
        }
    }

    private function checkDirectory(){

    }

}