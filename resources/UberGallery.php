<?php

/**
 * UberGallery is a simple PHP image gallery. (http://www.ubergallery.net)
 * @author Chris Kankiewicz (http://www.chriskankiewicz.com)
 * @copyright 2010 Chris Kankiewicz
 * @version 2.0.0-dev
 * 
 * Copyright (c) 2010 Chris Kankiewicz
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
class UberGallery {
    
    // Set some default variables
    protected $_cacheExpire = 0;
    protected $_imgPerPage  = 0;
    protected $_thumbSize   = 100;
    protected $_page        = 1;
    
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
        
        // Sanitize input and set current page
        if (isset($_GET['page'])) {
            $this->_page = (integer) $_GET['page'];
        } else {
            $this->_page = 1;
        }
        
        // Set class directory constant
        if(!defined('__DIR__')) {
            $iPos = strrpos(__FILE__, "/");
            define("__DIR__", substr(__FILE__, 0, $iPos) . "/");
        }
        
        // Get gallery configuration
        $this->_settings = parse_ini_file(__DIR__ . '/galleryConfig.ini', true);
        
        // Apply configuration
        $this->_cacheExpire = $this->_settings['basic_settings']['cache_expiration'];
        $this->_imgPerPage  = $this->_settings['basic_settings']['images_per_page'];
        $this->_thumbSize   = $this->_settings['basic_settings']['thumbnail_size'];
        $this->_cacheDir    = __DIR__ . '/' . $this->_settings['advanced_settings']['cache_directory'];
                
        // Set application directory and relative path
        $this->_workingDir  = getcwd();
        $this->_rThumbsDir  = substr($this->_cacheDir, strlen($this->_workingDir) + 1);
        
        // Check if cache directory exists and create it if it doesn't
        if (!file_exists($this->_cacheDir)) {
            if (!@mkdir($this->_cacheDir)) {
                die("<div style=\"background-color: #DDF; display: block; line-height: 1.4em; margin: 20px; padding: 20px; text-align: center;\">Unable to create cahe dir, plase manually create it. Try running <pre>mkdir {$this->_cacheDir}</pre></div>");
            }
        }
        
        // Check if cache directory is writeable and warn if it isn't
        if(!is_writable($this->_cacheDir)) {
            die("<div style=\"background-color: #DDF; display: block; line-height: 1.4em; margin: 20px; padding: 20px; text-align: center;\">Cache directory needs write permissions. If all else fails, try running: <pre>chmod 777 -R {$this->_cacheDir}</pre></div>");
        }
        
    }

    
    /**
     * UberGallery destruct function. Runs on object destruction.
     * TODO: Cache directory clean up
     */
    function __destruct() {
        // NULL
    }


    /**
     * Special factory method for simple one-line interface.
     */
    public static function factory() {
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
        echo '<!-- Start UberGallery ' . UberGallery::VERSION .' - Copyright (c) ' . date('Y') . ' Chris Kankiewicz (http://www.ChrisKankiewicz.com) -->' . PHP_EOL;
        echo '<div id="galleryWrapper">' . PHP_EOL;
        echo '    <ul id="galleryList" class="clearfix">' . PHP_EOL;
        foreach ($this->readImageDirectory($directory) as $image) {
            echo "            <li><a href=\"{$image['file_path']}\" title=\"{$image['file_title']}\" rel=\"colorbox\"><img src=\"{$image['thumb_path']}\" alt=\"{$image['file_title']}\"/></a></li>" . PHP_EOL;
        }
        echo '    </ul>' . PHP_EOL;
        echo '    <div id="galleryFooter" class="clearfix">' . PHP_EOL;
        echo '        <div id="credit">Powered by, <a href="http://www.ubergallery.net">UberGallery</a></div>' . PHP_EOL;
        echo '    </div>' . PHP_EOL;
        echo '</div>' . PHP_EOL;
        echo '<!-- End UberGallery - Dual licensed under the MIT & GPL license -->' . PHP_EOL;
        
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
            // TODO: Move this into a readDirectory function with ability to sort and paginate
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    
                    // Get files real path
                    $realPath = realpath($directory . '/' . $file);
                    
                    // Get files relative path
                    $relativePath = $this->_rImgDir . '/' . $file;
                    
                    
                    
                    // If file is an image, add info to array
                    if ($this->_isImage($realPath)) {
                        $imgArray[pathinfo($realPath, PATHINFO_BASENAME)] = array(
                            'file_title'   => str_replace('_', ' ', pathinfo($realPath, PATHINFO_FILENAME)),
                            'file_path'    => htmlentities($relativePath),
                            'thumb_path'   => $this->_createThumbnail($realPath)
                        );
                    }
                }
            }
            
            // Close open file handle
            closedir($handle);
            
        }
        
        // Create empty array
        $sortedArray = array();
        
        // Create new array of just the keys and sort it
        $keys = array_keys($imgArray); 
        natcasesort($keys);
        
        // Loop through the sorted values and move over the data
        foreach ($keys as $key) {
            $sortedArray[$key] = $imgArray[$key];
        }
        
        // Save the sorted array
        $this->_createIndex($sortedArray);
        
        // Paginate the array and return current page
        $finalArray = $this->_arrayPaginate($sortedArray, $this->_imgPerPage, $this->_page);

        // Return the array
        return $finalArray;
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
     * Sorts an array
     * @param string $array Array to be sorted
     * @param string $sort Sorting method (acceptable inputs: natsort, natcasesort, etc.)
     * @return array
     */
    protected function _arraySort($array, $sort) {
        
    }
    
    
    /**
     * Paginates array and returns partial array of current page
     * @param string $array Array to be paginated
     * @return array
     */
    protected function _arrayPaginate($array, $resultsPerPage, $currentPage) {
        
        // Page varriables
        $totalElements = count($array);
        
        if ($resultsPerPage <= 0 || $resultsPerPage >= $totalElements) {
            $firstElement = 0;
            $lastElement = $totalElements;
            $totalPages = 1;
        } else {
            // Calculate total pages
            $totalPages = ceil($totalElements / $resultsPerPage);
            
            // Set current page
            if ($currentPage < 1) {
                $currentPage = 1;
            } elseif ($currentPage > $totalPages) {
                $currentPage = $totalPages;
            } else {
                $currentPage = (integer) $currentPage;
            }
            
            // Calculate starting image
            $firstElement = ($currentPage - 1) * $resultsPerPage;
            
            // Calculate last image
            if($currentPage * $resultsPerPage > $totalElements) {
                $lastElement = $totalElements;
            } else {
                $lastElement = $currentPage * $resultsPerPage;
            }
        }
        
        // Initiate counter
        $x = 1;
        
        // Run loop to paginate images and add them to array
        foreach ($array as $key => $element) {
            
            // Add image to array if within current page
            if ($x > $firstElement && $x <= $lastElement) {
                $paginatedArray[$key] = $array[$key];
            }
            
            // Increment counter
            $x++;
        }
        
        // Return paginated array
        return $paginatedArray;
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
