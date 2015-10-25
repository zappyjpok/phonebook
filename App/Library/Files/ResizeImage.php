<?php

namespace App\library;

class ResizeImage {

    protected $createThumbnail = false;
    protected $originalFile;
    protected $originalFilePath;
    protected $oldHeight;
    protected $oldWidth;
    protected $newHeight;
    protected $newWidth;
    protected $extension;
    protected $image;

    /**
     * When Initialize it will set the path and the desired height and width
     *
     * @param $originalFile
     * @param $width
     * @param $height
     */
    public function __construct($originalFile, $width, $height)
    {

        if ($this->checkFile($originalFile) == true) {
            $this->originalFile = $originalFile;
            $this->newHeight = $height;
            $this->newWidth = $width;
        } else {
            throw new \Exception($originalFile . ' does not exist');
        }


    }

    /**
     * Check if the files exists before continuing
     *
     * @param $file
     * @return bool
     */
    private function checkFile($file) {
        if(file_exists($file)){
            return true;
        } else {
            return false;
        }
    }


    // This image will resize the image
    public function createResizeImage()
    {
        $this->getFileExtension();
        $this->getDimensions();
        $this->createImageVariable();
        $this->createImage();
    }

    /**
     * Create a thumbnail when you resize original image
     *
     * @param $height
     * @param $width
     */
    public function createThumbNail($height, $width)
    {
        $this->newHeight = $height;
        $this->newWidth = $width;
        $this->getFileExtension();
        $this->getDimensions();
        $this->createImageVariable();
        $this->createFileName();
        $this->createImage();
    }

    /**
     * Function to get the file extension to use in the function
     *
     *
     */
    protected function getFileExtension() {
        $nameParts = pathinfo($this->originalFile);
        $this->extension = ($nameParts['extension']);
    }

    /**
     * Function to get the new scaled down dimensions of the image
     */
    protected function getDimensions()
    {

        list($originalWidth, $originalHeight) = getimagesize($this->originalFile);

        //get original sizes to create the function
        $this->oldHeight = $originalHeight;
        $this->oldWidth = $originalWidth;


        $scaleRatio = $originalWidth / $originalHeight;
        if (($this->newWidth/$this->newHeight) > $scaleRatio){
            $this->newWidth = $this->newHeight * $scaleRatio;
        } else {
            $this->newHeight = $this->newWidth/$scaleRatio;
        }
    }

    // Function to set the image variable to use in the image copy function
    protected function createImageVariable()
    {
        $ext = strtolower($this->extension);
        if($ext == 'gif') {
            $this->image = imagecreatefromgif($this->originalFile);
        } else if ($ext == 'png') {
            $this->image = imagecreatefrompng($this->originalFile);
        } else {
            $this->image = imagecreatefromjpeg($this->originalFile);
        }
    }

    // create the new path and name for the file with
    protected function createFileName()
    {
        $nameParts = pathinfo($this->originalFile);
        $this->originalFile = $nameParts['dirname'] . '/' . $nameParts['filename'] . '_tn' . '.' . $this->extension;
    }

    // Create the resized image
    protected function createImage()
    {
        $resource = imagecreatetruecolor($this->newWidth, $this->newHeight);

        imagecopyresampled($resource, $this->image, 0, 0, 0, 0,
            $this->newWidth, $this->newHeight, $this->oldWidth, $this->oldHeight);
        imagejpeg($resource, $this->originalFile, 80);
    }


}