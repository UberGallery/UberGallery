<?php

new UberGallery();

/**
 *
 * UberGallery is a simple PHP image gallery.
 * @author Chris Kankiewicz (http://www.ubergallery.net)
 * @copyright 2010 Chris Kankiewicz
 * @version 2.0.0
 *
 */

class UberGallery {

    /**
     *
     * @param string $fileDir Relative path where /image and /cache are located
     * @param int $thumbSize Image thumbnail size
     * @param int $imgPerPage Number of images per page
     *
     */
    function __construct($imgDir = './gallery-images', $thumbSize = 100, $imgPerPage = 0) {

        define('VERSION', '2.0.0');
        define('APP_DIR', './.ubergallery');
        define('CACHE_DIR', APP_DIR . '/cache');
        define('INDEX', APP_DIR . 'index');
        
        // Check if application directory exists or create it if it does not
        if (!file_exists(APP_DIR)) {
            mkdir(APP_DIR);
        }
        
        // Check if cache directory exists or create it if it does not
        if (!file_exists(CACHE_DIR)) {
            mkdir(CACHE_DIR);
        }
        
        print_r($this->readImageDir($imgDir));
    }
        
    function __destruct() {
        echo PHP_EOL . '<br/>END OF LINE';
    }
    

    /**
     * Opens and writes to log file
     * @param string $logText
     */
    protected function writeToLog($logText) {      
        // Open log for appending
        $logPath = APP_DIR . '/log.txt';
        $log = fopen($logPath, 'a');
          
        // Get current time
        $currentTime = date("Y-m-d H:i:s");
        
        // Write text to log
        fwrite($log, '[' . $currentTime . '] ' . $logText . PHP_EOL);
        
        // Close open file pointer
        fclose($log);
    }
    
    protected function readImageDir($directory) {
        
        $imgArray = array();
        
        if ($handle = opendir($directory)) {
            
            // Loop through directory and add information to array
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    
                    // Add file and meta-info to array
                    $imgArray[] = array(
                        'file_name'   => $file,
                        'file_title'  => str_replace('_', ' ', pathinfo($file, PATHINFO_FILENAME)),
                        'file_path'   => realpath($directory . '/' . $file),
                        'file_hash'   => md5($file),
                        'file_mime'	  => @exif_imagetype($file)
                    );
                }
            }
            
            // Close open file handle
            closedir($handle);
            
        }
        
        // Return the array
        return $imgArray;
    }

    protected function readIndex() {
        // Open index for reading
        $index = fopen(INDEX, 'r');
        
        // Explode into an array
        $indexArray = explode("\t", $index);
        
        return $indexArray;
    }

    protected function createIndex() {

    }
    
    /**
     * Verifies wether or not a file is an image
     * @param string $fileName
     * @return boolean
     */
    protected function isImage($fileName) {
		@$imgInfo = getimagesize($fileName);

		$imgType = array(
			IMAGETYPE_JPEG,
			IMAGETYPE_GIF,
			IMAGETYPE_PNG,
		);

		if (in_array($imgInfo[2], $imgType)) {
		    return true;
		} else {
		    return false;
		}
    }

}


?>