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
    protected $_appDir      = NULL;
    protected $_cacheDir    = NULL;
    protected $_index       = NULL;
    
    // Define application version
    const VERSION = '2.0.0-dev';
    
    // Set default application directory
    const APP_DIR = './.ubergallery';
    
    /**
     * UberGallery construct function.  Runs on object creation
     * @param string $imgDir
     * @param int $cacheExpire
     * @param int $thumbSize
     * @param int $imgPerPage
     */
    function __construct($imgDir = './gallery-images', $cacheExpire = 0, $thumbSize = 100, $imgPerPage = 0) {
        
        // Set global variables
        $this->_imgDir      = $imgDir;
        $this->_cacheExpire = $cacheExpire;
        $this->_thumbSize   = $thumbSize;
        $this->_imgPerPage  = $imgPerPage;
        
        // Set application directory and file variables
        $this->_appDir      = realpath(self::APP_DIR);
        $this->_cacheDir    = $this->_appDir . '/cache';
        $this->_index       = $this->_appDir . '/images.index';
        
        // Check if application directory exists and create it if it does not
        if (!file_exists($this->_appDir)) {
            if (mkdir($this->_appDir)) {
                $this->writeToLog('Created application directory in ' . $this->_appDir);
            } else {
                $this->writeToLog('ERROR: Failed to create application directory in ' . $this->_appDir);                
            }
        }
        
        // Check if cache directory exists and create it if it does not
        if (!file_exists($this->_cacheDir)) {
            if (mkdir($this->_cacheDir)) {
                $this->writeToLog('Created cache directory in ' . $this->_cacheDir);
            } else {
                $this->writeToLog('ERROR: Failed to create cache directory in ' . $this->_cacheDir);                
            }
        }
        
        print_r($this->_readImageDirectory($imgDir));
    }
        
    function __destruct() {
        echo PHP_EOL . '<br/>END OF LINE';
    }
    
    
    /**
     * Returns an array of files in the specified directory
     * @param string $directory
     */
    protected function _readImageDirectory($directory) {
        
        $imgArray = array();
        
        // Return the cached array if it exists.
        if ($imgArray = $this->_readIndex()) {
            return $imgArray;
        }
        
        if ($handle = opendir($directory)) {
            
            // Loop through directory and add information to array
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    
                    // Get files real path
                    $realPath = realpath($directory . '/' . $file);
                    
                    // Add file and meta-info to array
                    $imgArray[] = array(
                        'file_name'   => pathinfo($realPath, PATHINFO_BASENAME),
                        'file_title'  => str_replace('_', ' ', pathinfo($realPath, PATHINFO_FILENAME)),
                        'file_path'   => $realPath,
                        'file_hash'   => md5($realPath),
                        'file_mime'   => @exif_imagetype($realPath)
                    );
                }
            }
            
            // Close open file handle
            closedir($handle);
            
        }
        
        $this->_createIndex($imgArray);
        
        // Return the array
        return $imgArray;
    }

    
    /**
     * Create thumbnail, modified from function found on http://www.findmotive.com/tag/php/
     * Creates a cropped, square thumbnail of given dimensions from a source image
     * @param string $source
     * @param string $dest
     * @param int $thumb_size
     */
    protected function _createThumbnail($source, $destination, $thumbSize, $quality = 75) {
    	$imgInfo = getimagesize($source);
    	$width = $imgInfo[0];
    	$height = $imgInfo[1];
    
    	if ($width > $height) {
    		$x = ceil(($width - $height) / 2 );
    		$width = $height;
    	} elseif($height > $width) {
    		$y = ceil(($height - $width) / 2);
    		$height = $width;
    	}
    
    	$new_im = imagecreatetruecolor($thumbSize,$thumbSize);
    
    	if ($imgInfo[2] == IMAGETYPE_JPEG) {
    		$image = imagecreatefromjpeg($source);
    		imagecopyresampled($newImage, $image, 0, 0, $x, $y, $thumbSize, $thumbSize, $width, $height);
    		imagejpeg($newImage, $destination, $quality); // Thumbnail quality (Value from 1 to 100)
    	} elseif ($imgInfo[2] == IMAGETYPE_GIF) {
    		$image = imagecreatefromgif($source);
    		imagecopyresampled($newImage, $image, 0, 0, $x, $y, $thumbSize, $thumbSize, $width, $height);
    		imagegif($newImage, $destination);
    	} elseif ($imgInfo[2] == IMAGETYPE_PNG) {
    		$image = imagecreatefrompng($source);
    		imagecopyresampled($newImage, $image, 0, 0, $x, $y, $thumbSize, $thumbSize, $width, $height);
    		imagepng($newImage, $destination);
    	}
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
        if(!isset($filePath)) {
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
    protected function _isImage($fileName) {
        
        // Get real path of the file
        $realPath = realpath($directory . '/' . $file);
        
        // Get file type
        $imgType = @exif_imagetype($realPath);

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
        $logPath = $this->_appDir . '/log.txt';
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