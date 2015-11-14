<?php


class UploadImage
{
    protected $destination;
    protected $maxSize = 51200;
    protected $newName;
    protected $typeCheckingOn = true;
    protected $renameDuplicates;
    protected $suffix = '.upload';
    protected $errors = [];
    protected $success = [];
    protected $fileNames = [];
    protected $permittedTypes = [
        'image/jpeg',
        'image/pjpeg',
        'image/gif',
        'image/png',
        'image/webp'
    ];
    protected $noTrusted = ['bin', 'cgi', 'exe', 'js', 'pl', 'php', 'py', 'sh'];

    /**
     * Test if the folder is writable and if the dir exists
     *
     * @param $uploadFolder the folder where the imagmes will be uploaded
     * @throws \Exception
     */
    public function __construct($uploadFolder)
    {
        if (!is_dir($uploadFolder) || !is_writable($uploadFolder)) {
            throw new \Exception("$uploadFolder must be a valid, writable folder.");
        }
        if ($uploadFolder[strlen($uploadFolder)-1] != '/') {
            $uploadFolder .= '/';
        }
        $this->destination = $uploadFolder;
    }

    /**
     * sets the max size of the upload file
     *
     * @param $bytes
     * @throws \Exception
     */
    public function setMaxSize($bytes)
    {
        $serverMax = self::convertToBytes(ini_get('upload_max_filesize'));
        if($bytes > $serverMax) {
            throw new \Exception('Maximum size cannot exceed server limit for individual files: ' .
            self::convertFromBytes($serverMax));
        }
        if(is_numeric($bytes) && $bytes > 0 ) {
            $this->maxSize = $bytes;
        }
    }

    /**
     * Converts a value to use bytes
     *
     * @param $val
     * @return int|string
     */
    public static function convertToBytes($val){
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        if (in_array($last, array('g', 'm', 'k'))){
            switch($last){
                case 'g':
                    $val *= 1024;
                case 'm':
                    $val *= 1024;
                case 'k':
                    $val *= 1024;
            }
        }
        return $val;
    }

    /**
     * Convert value from bytes
     *
     * @param $bytes
     * @return string
     */
    public static function convertFromBytes($bytes)
    {
        $bytes /= 1024;
        if ($bytes > 1024) {
            return number_format($bytes/1024, 1) . ' MB';
        } else {
            return number_format($bytes, 1) . ' KB';
        }
    }

    /**
     * This function turns fo the check file feature
     *
     * @param null $suffix
     */
    public function allowAllTypes($suffix = null)
    {
        $this->typeCheckingOn = false;
        if (!is_null($suffix)) {
           if(strpos($suffix, '.') === 0 || $suffix == "") {
               $this->suffix = $suffix;
           } else {
               $this->suffix = ".$suffix";
           }
        }
    }

    /**
     * uploads the file to the selected folder
     *
     * @param bool $renameDuplicates
     */
    public function upload($renameDuplicates = true)
    {
        $this->renameDuplicates = $renameDuplicates;
        $uploaded = current($_FILES);

        if(is_array($uploaded['name']))
        {
            foreach($uploaded['name'] as $key => $value)
            {
                $currentFile['name'] = $uploaded['name'][$key];
                $currentFile['type'] = $uploaded['type'][$key];
                $currentFile['tmp_name'] = $uploaded['tmp_name'][$key];
                $currentFile['error'] = $uploaded['error'][$key];
                $currentFile['size'] = $uploaded['size'][$key];
                if ($this->checkFile($currentFile))
                {
                    $this->moveFile($currentFile);
                }
            }
        } else {
            if ($this->checkFile($uploaded))
            {
                $this->moveFile($uploaded);
            }
        }

    }


    public function getName()
    {
        return $this->fileNames;
    }

    /**
     * Returns an array of errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns a success message
     *
     * @return array
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Check the upload file or files for errors
     *
     * @param $file
     * @return bool
     */
    protected function checkFile($file)
    {
        if ($file['error'] != 0) {
            $this->getErrorMessage($file);
            return false;
        }
        if(!$this->checkSize($file)){
            return false;
        }
        if ($this->typeCheckingOn) {
            if (!$this->checkType($file)) {
                return false;
            }
        }
        $this->checkName($file);
        return true;
    }

    /**
     * Create error messages
     *
     * @param $file
     */
    protected function getErrorMessage($file)
    {
        switch($file['error']) {
            case 1:
            case 2:
                $this->errors[] = $file['name'] . ' is too big: (max: ' .
                self::convertFromBytes($this->maxSize) . ').';
                break;
            case 3:
                $this->errors[] = $file['name'] . ' was only partially uploaded';
                break;
            case 4:
                $this->errors[] = 'No file was submitted';
                break;
            default:
                $this->errors[] = 'Sorry, there was a problem uploading ' . $file['name'];
                break;
        }
    }

    /**
     * Checks the file size
     *
     * @param $file
     * @return bool
     */
    protected function checkSize($file)
    {
        if ($file['size'] == 0) {
            $this->errors[] = $file['name'] . ' is empty.';
            return false;
        } else if ($file['size'] > $this->maxSize){
            $this->errors[] = $file['name'] . ' exceeds the maximum size for a file ('
                . self::convertFromBytes($this->maxSize) . ').';
            return false;
        } else {
            return true;
        }
    }

    /**
     * Checks if the file submitted is permitted
     *
     * @param $file
     * @return bool
     */
    protected function checkType($file)
    {
        if (in_array($file['type'], $this->permittedTypes)){
            return true;
        } else {
            $this->errors[] = $file['name'] . ' is not a permitted type of file.';
            return false;
        }
    }

    /**
     * Checks the name to prevent duplication
     *
     * @param $file
     */
    protected function checkName($file)
    {
        $this->newName = null;
        $noSpaces = str_replace(' ', '_', $file['name']);
        if ($noSpaces != $file['name']) {
            $this->newName = $noSpaces;
        }
        $nameParts = pathinfo($noSpaces);
        $extension = isset($nameParts['extension']) ? $nameParts['extension'] : '';
        if (!$this->typeCheckingOn && !empty($this->suffix)) {
            if (in_array($extension, $this->noTrusted)){
                $this->newName = $noSpaces . $this->suffix;
            }
        }
        if ($this->renameDuplicates) {
            $name = isset($this->newName) ? $this->newName : $file['name'];
            $existing = scandir($this->destination);
            if (in_array($name, $existing)) {
                $i = 1;
                do {
                    $this->newName = $nameParts['filename'] . '_' . $i++;
                    if (!empty($extension)) {
                        $this->newName .= ".$extension";
                    }
                    if (in_array($extension, $this->noTrusted)) {
                        $this->newName .=$this->suffix;
                    }
                } while (in_array($this->newName, $existing));
            }
        }

    }

    protected function moveFile($file)
    {
        $fileName = isset($this->newName) ? $this->newName : $file['name'];
        $success = move_uploaded_file($file['tmp_name'], $this->destination . $fileName);
        if ($success) {
            $result = $file['name'] . ' was uploaded successfully';
            $this->fileNames [] = $fileName;
            if (!is_null($this->newName)) {
                $result .= ', and was renamed ' . $this->newName;
            }
            $result .= '.';
            $this->success[] = $result;
        } else {
            $this->errors[] = 'Could not upload' . $file['name'];
        }
    }



}