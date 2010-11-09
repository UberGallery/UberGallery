<?php

/**
 * UberGallery is a simple PHP image gallery. (http://www.ubergallery.net)
 * @author Chris Kankiewicz (http://www.chriskankiewicz.com)
 * @copyright 2010 Chris Kankiewicz
 * @version 2.0.0-dev
 * 
 * TODO: Pagination
 */
class UberGallery {
    
    // Set some default variables
    protected $_cacheExpire = 0;
    protected $_imgPerPage  = 0;
    protected $_thumbSize   = 100;
    
    // Reserve some other variables
    protected $_imgDir      = NULL;
    protected $_appDir      = NULL;
    protected $_workingDir  = NULL;
    protected $_cacheDir    = NULL;
    protected $_index       = NULL;
    protected $_rThumbsDir  = NULL;
    protected $_rImgDir     = NULL;
    
    // Define application version
    const VERSION = '2.0.0-dev';
    
    
    /**
     * UberGallery construct function. Runs on object creation.
     */
    function __construct() {
        
        // Set class directory constant
        if(!defined('__DIR__')) {
            $iPos = strrpos(__FILE__, "/");
            define("__DIR__", substr(__FILE__, 0, $iPos) . "/");
        }
        
        // Set application directory and file paths
        $this->_workingDir  = getcwd();
        $this->_cacheDir    = __DIR__ . '/cache';
        $this->_rThumbsDir  = substr($this->_cacheDir, strlen($this->_workingDir) + 1);
        
        // Check if cache directory exists and create it if it doesn't
        if (!file_exists($this->_cacheDir)) {
            mkdir($this->_cacheDir);
        }
        
        // Check if cache directory is writeable and warn if it isn't
        if(!is_writable($this->_cacheDir)) {
            die("Cache directory needs write permissions. If all else fails, try running: <pre>chmod 777 -R {$this->_cacheDir}</pre>");
        }
        
    }

    
    /**
     * UberGallery destruct function. Runs on object destruction.
     * TODO: Cache directory clean up
     */
    function __destruct() {
        
    }


    /**
     * Special factory method for simple one-line interface.
     */
    public static function factory()
    {
        $reflection = new ReflectionClass(__CLASS__);
        return $reflection->newInstanceArgs(func_get_args());
    }

    
    /**
     * Returns formatted HTML of a gallery
     * @param string $directory Relative path to images directory
     */
    public function createGallery($directory) {
        
        // Set relative image directory
        $this->setRelativeImageDirectory($directory);
        
        // Echo formatted gallery markup
        echo '<!-- Start UberGallery ' . UberGallery::VERSION .' - Copyright (c) ' . date('Y') . ' Chris Kankiewicz (http://www.ChrisKankiewicz.com) -->';
        echo '    <ul id="galleryList" class="clearfix">';
        foreach ($this->readImageDirectory($directory) as $image) {
            echo "            <li><a href=\"{$image['file_path']}\" title=\"{$image['file_title']}\" rel=\"colorbox\"><img src=\"{$image['thumb_path']}\" alt=\"{$image['file_title']}\"/></a></li>";
        }
        echo '    </ul>';
        echo '    <div id="galleryFooter" class="clearfix">';
        echo '        <div id="credit">Powered by, <a href="http://www.ubergallery.net">UberGallery</a></div>';
        echo '    </div>';
        echo '<!-- End UberGallery - Dual licensed under the MIT & GPL license -->';
        
        return $this;
    }
    
    
    /**
     * Returns an array of files in the specified directory
     * @param string $directory Relative path to images directory
     */
    public function readImageDirectory($directory) {
        
        // Set relative image directory
        $this->setRelativeImageDirectory($directory);
        
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
            // TODO: Move this into a readDirectory function with ability to sort
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
    
    
    /**
     * Returns script version
     */
    public function readVersion() {
        return UberGallery::VERSION;
    }

    /**
     * Set cache expiration time in minutes
     * @param unknown_type $time Cache expiration time in minutes
     */
    public function setCacheExpiration($time) {
        $this->_cacheExpire = $time;
        
        return $this;
    }
    
    
    /**
     * Set the number of images to be displayed per page
     * @param unknown_type $imgPerPage Number of images to display per page
     */
    public function setImagesPerPage($imgPerPage) {
        $this->_imgPerPage = $imgPerPage;
        
        return $this;
    }
    
    
    /**
     * Set thumbnail size
     * @param unknown_type $size Thumbnail size
     */
    public function setThumbSize($size) {
        $this->_thumbSize = $size;
        
        return $this;
    }
    
    
    public function setCacheDirectory($directory) {
        $this->_cacheDir = realpath($directory);
        
        return $this;
    }
    
    
    /**
     * Sets the relative path to the image directory
     * @param string $directory Relative path to image directory
     */
    public function setRelativeImageDirectory($directory) {
        $this->_imgDir  = realpath($directory);
        $this->_rImgDir = $directory;
        $this->_index   = $this->_cacheDir . '/' . md5($directory) . '.index';
        
        return $this;
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
