<?php

/**
 * UberGallery is a simple PHP image gallery.
 * @author Chris Kankiewicz (http://www.ubergallery.net)
 * @copyright 2010 Chris Kankiewicz
 * @version 2.0.0-dev
 * 
 * @param string $fileDir Relative path where /image and /cache are located
 * @param int $thumbSize Image thumbnail size
 * @param int $imgPerPage Number of images per page
 */
class UberGallery {
    
    // Reserve some default variables
    protected $_imgDir      = NULL;
    protected $_cacheExpire = NULL;
    protected $_thumbSize   = NULL;
    protected $_imgPerPage  = NULL;
    
    // Reserve application directory and file variables
    protected $_workingDir  = NULL;
    protected $_cacheDir    = NULL;
    protected $_index       = NULL;
    protected $_rThumbsDir  = NULL;
    protected $_rImgDir     = NULL;
    
    // Define application version
    const VERSION = '2.0.0-dev';
    
    /**
     * UberGallery construct function.  Runs on object creation
     * @param string $imgDir
     * @param int $cacheExpire
     * @param int $thumbSize
     * @param int $imgPerPage
     */
    function __construct($imgDir, $cacheExpire = 0, $thumbSize = 100, $imgPerPage = 0) {
        
        // Set global variables
        $this->_imgDir      = realpath($imgDir);
        $this->_cacheExpire = $cacheExpire;
        $this->_thumbSize   = $thumbSize;
        $this->_imgPerPage  = $imgPerPage;
        
        // Set application directory and file paths
//        $this->_appDir      = realpath(self::APP_DIR);
        $this->_workingDir  = getcwd();
        $this->_cacheDir    = $this->_workingDir . '/cache';
        $this->_index       = $this->_cacheDir . '/' . md5($imgDir) . '.index';
        $this->_rThumbsDir  = 'cache';
        $this->_rImgDir     = $imgDir;
        
        // Check if cache directory exists and create it if it does not
        if (!file_exists($this->_cacheDir)) {
            mkdir($this->_cacheDir);
        }
        
//        print_r($this->_readImageDirectory($imgDir));
    }
        
    function __destruct() {
        // echo PHP_EOL . '<br/>END OF LINE';
    }
    
    
    /**
     * Returns an array of files in the specified directory
     * @param string $directory
     */
    public function readImageDirectory($directory = NULL) {
        
        // Set defaults image directory if not specified
        if ($directory === NULL) {
            $directory = $this->_rImgDir;
        }
        
        // Instantiate image array
        $imgArray = array();
        
        // Return the cached array if it exists.
        if (file_exists($this->_index)) {
            if ((time() - filemtime($this->_index)) / 60 < $this->_cacheExpire) {
                if ($imgArray = $this->_readIndex()) {
                    return $imgArray;
                }
            }
        }
        
        if ($handle = opendir($directory)) {
            
            // Loop through directory and add information to array
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    
                    // Get files real path
                    $realPath = realpath($directory . '/' . $file);
                    
                    // Get files relative path
                    $relativePath = $this->_rImgDir . '/' . $file;
                    
                    // If file is an image, add info to array
                    if ($this->_isImage($realPath)) {
                        $imgArray[] = array(
                            'file_name'    => pathinfo($realPath, PATHINFO_BASENAME),
                            'file_title'   => str_replace('_', ' ', pathinfo($realPath, PATHINFO_FILENAME)),
                            'file_path'    => $relativePath,
//                          'file_hash'    => md5($realPath),
//                          'file_mime'    => @exif_imagetype($realPath),
                            'thumb_path'   => $this->_createThumbnail($realPath)
                        );
                    }
                }
            }
            
            // Close open file handle
            closedir($handle);
            
        }
        
        $this->_createIndex($imgArray);
        
        // Return the array
        return $imgArray;
    }
    
    public function readVersion() {
        return UberGallery::VERSION;
    }

    
    /**
     * Create thumbnail, modified from function found on http://www.findmotive.com/tag/php/
     * Creates a cropped, square thumbnail of given dimensions from a source image
     * @param string $source
     * @param int $thumb_size
     * @param int $quality Thumbnail quality (Value from 1 to 100)
     */
    protected function _createThumbnail($source, $thumbSize = NULL, $quality = 75) {
        
        // Set defaults thumbnail size if not specified
        if ($thumbSize === NULL) {
            $thumbSize = $this->_thumbSize;
        }
        
        // MD5 hash of source image
        $fileHash = md5_file($source);
        
        // Get file extension from source image
        $fileExtension = pathinfo($source, PATHINFO_EXTENSION);
        
        // Build file name
        $fileName = $thumbSize . '-' . $fileHash . '.' . $fileExtension;
        
        // Build thumbnail destination path
        $destination = $this->_cacheDir . '/' . $fileName;
        
        // If file already exists return relative path to thumbnail
        if (file_exists($destination)) {
            $relativePath = $this->_rThumbsDir . '/' . $fileName;
            return $relativePath;
        }
        
        // Get needed image information
        $imgInfo = getimagesize($source);
        $width = $imgInfo[0];
        $height = $imgInfo[1];
        $x = 0;
        $y = 0;

        // Make the image a square
        if ($width > $height) {
            $x = ceil(($width - $height) / 2 );
            $width = $height;
        } elseif($height > $width) {
            $y = ceil(($height - $width) / 2);
            $height = $width;
        }

        // Create new empty image of proper dimensions
        $newImage = imagecreatetruecolor($thumbSize,$thumbSize);

        // Create new thumbnail
        if ($imgInfo[2] == IMAGETYPE_JPEG) {
            $image = imagecreatefromjpeg($source);
            imagecopyresampled($newImage, $image, 0, 0, $x, $y, $thumbSize, $thumbSize, $width, $height);
            imagejpeg($newImage, $destination, $quality);
        } elseif ($imgInfo[2] == IMAGETYPE_GIF) {
            $image = imagecreatefromgif($source);
            imagecopyresampled($newImage, $image, 0, 0, $x, $y, $thumbSize, $thumbSize, $width, $height);
            imagegif($newImage, $destination);
        } elseif ($imgInfo[2] == IMAGETYPE_PNG) {
            $image = imagecreatefrompng($source);
            imagecopyresampled($newImage, $image, 0, 0, $x, $y, $thumbSize, $thumbSize, $width, $height);
            imagepng($newImage, $destination);
        }
        
        
        // Return relative path to thumbnail
        $relativePath = $this->_rThumbsDir . '/' . $fileName;
        return $relativePath;
    }
    
    
    /**
     * Return array from the index
     * @param string $filePath
     * @return ArrayObject
     */
    protected function _readIndex($filePath = NULL) {
        // Set file path if not specified
        if(!isset($filePath)) {
            $filePath = $this->_index;
        }
        
        // Return false if file doesn't exist
        if (!file_exists($filePath)) {
            return false;
        }
        
        // Read index and unsearialize the array
        $index = fopen($filePath, 'r');
        $indexString = fread($index,filesize($filePath));
        $indexArray = unserialize($indexString);
        
        // Return the array
        return $indexArray;
    }

    
    /**
     * Create index from file array
     * @param string $array
     * @param string $filePath
     * @return boolean
     */
    protected function _createIndex($array, $filePath = NULL) {
        // Set file path if not specified
        if($filePath === NULL) {
            $filePath = $this->_index;
        }
        
        // Serialize array and write it to the index
        $index = fopen($filePath, 'w');
        $serializedArray = serialize($array);
        fwrite($index, $serializedArray);
        
        return true;
    }
    
    
    /**
     * Verifies wether or not a file is an image
     * @param string $fileName
     * @return boolean
     */
    protected function _isImage($filePath) {
        
        // Get file type
        $imgType = @exif_imagetype($filePath);

        // Array of accepted image types
        $allowedTypes = array(1, 2, 3);

        // Determine if the file type is an acceptable image type
        if (in_array($imgType, $allowedTypes)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    /**
     * Opens and writes to log file
     * @param string $logText
     */
    protected function _writeToLog($logText) {      
        // Open log for appending
        $logPath = $this->_cacheDir . '/log.txt';
        $log = fopen($logPath, 'a');
          
        // Get current time
        $currentTime = date("Y-m-d H:i:s");
        
        // Write text to log
        fwrite($log, '[' . $currentTime . '] ' . $logText . PHP_EOL);
        
        // Close open file pointer
        fclose($log);
    }

}

?>