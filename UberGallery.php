<?php

/**
 *
 * UberGallery is a simple PHP image gallery.
 * @author Chris Kankiewicz (http://www.ubergallery.net)
 * @copyright 2010 Chris Kankiewicz
 * @version 2.0.0-dev
 * 
 * @param string $fileDir Relative path where /image and /cache are located
 * @param int $thumbSize Image thumbnail size
 * @param int $imgPerPage Number of images per page
 *
 */

class UberGallery {
    
    // Set some default variables
    protected $_imgDir     = NULL;
    protected $_thumbSize  = NULL;
    protected $_imgPerPage = NULL;
    
    // Define application version
    const VERSION = '2.0.0';
    
    // Set default application directory
    const APP_DIR = './.ubergallery';
    
    // Reserve application directory and file variables
    protected $_appDir     = NULL;
    protected $_cacheDir   = NULL;
    protected $_index      = NULL;
    
    
    function __construct($imgDir = './gallery-images', $thumbSize = 100, $imgPerPage = 0) {
        
        // Set global variables
        $this->_imgDir = $imgDir;
        $this->_thumbSize = $thumbSize;
        $this->_imgPerPage = $imgPerPage;
        
        // Set application directory and file variables
        $this->_appDir     = realpath(self::APP_DIR);
        $this->_cacheDir   = $this->_appDir . '/cache';
        $this->_index      = $this->_appDir . '/images.index';
        
        // Check if application directory exists or create it if it does not
        if (!file_exists($this->_appDir)) {
            if (mkdir($this->_appDir)) {
                $this->writeToLog('Created application directory in ' . $this->_appDir);
            } else {
                $this->writeToLog('ERROR: Failed to create application directory in ' . $this->_appDir);                
            }
        }
        
        // Check if cache directory exists or create it if it does not
        if (!file_exists($this->_cacheDir)) {
            mkdir($this->_cacheDir);
        }
        
        print_r($this->readImageDirectory($imgDir));
    }
        
    function __destruct() {
        echo PHP_EOL . '<br/>END OF LINE';
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
    
    protected function _readImageDirectory($directory) {
        
        $imgArray = array();
        
        if ($handle = opendir($directory)) {
            
            // Loop through directory and add information to array
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    
                    // Get files real path
                    $realPath = realpath($directory . '/' . $file);
                    
                    // Add file and meta-info to array
                    $imgArray[] = array(
                        'file_name'   => $file,
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
        
        // Return the array
        return $imgArray;
    }

    protected function _readIndex() {
        // Open index for reading
        $index = fopen($this->_index, 'r');
        
        // Explode into an array
        $indexArray = explode("\t", $index);
        
        return $indexArray;
    }

    protected function _createIndex() {

    }
    
    /**
     * Verifies wether or not a file is an image
     * @param string $fileName
     * @return boolean
     */
    protected function _isImage($fileName) {
        
        // Get files real path
        $realPath = realpath($directory . '/' . $file);
        
        $imgType = @exif_imagetype($realPath);

        $allowedTypes = array(
            IMAGETYPE_JPEG,
            IMAGETYPE_GIF,
            IMAGETYPE_PNG,
        );

        if (in_array($imgType, $allowedTypes)) {
            return true;
        } else {
            return false;
        }
    }

}


?>