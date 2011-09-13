<?php

/**
 * UberGallery is an easy to use, simple to manage, web photo gallery written in
 * PHP. UberGallery does not require a database and supports JPEG, GIF and PNG 
 * file types. Simply upload your images and UberGallery will automatically 
 * generate thumbnails and output standards complaint XHTML markup on the fly.
 * 
 * This software is dual liscensed under the following licenses:
 *     MIT License      http://www.ubergallery.net/COPYING-MIT.txt
 *     GPL Version 3    http://www.ubergallery.net/COPYING-GPL.txt
 * 
 * More info available at http://www.ubergallery.net
 * 
 * @author Chris Kankiewicz (http://www.chriskankiewicz.com)
 * @copyright 2011 Chris Kankiewicz
 */
class UberGallery {
    
    // Define application version
    const VERSION = '2.2.4';
    
    // Set default config variables
    protected $_cacheExpire = 0;
    protected $_imgPerPage  = 0;
    protected $_thumbSize   = 100;
    protected $_themeName   = 'uber-blue';
    protected $_page        = 1;
    protected $_cacheDir    = 'cache';
    protected $_imgSortBy   = 'natcasesort';
    
    // Reserve some other variables
    protected $_imgDir      = NULL;
    protected $_appDir      = NULL;
    protected $_index       = NULL;
    protected $_rThumbsDir  = NULL;
    protected $_rImgDir     = NULL;
    
    
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
            define("__DIR__", substr(__FILE__, 0, $iPos));
        }
        
        // Set application directory
        $this->_appDir = __DIR__;
        
        // Set configuration file path
        $configPath = $this->_appDir . '/galleryConfig.ini';
        
        // Read and apply gallery config or throw error on fail
        if (file_exists($configPath)) {
            // Parse gallery configuration
            $config = parse_ini_file($configPath, true);
            
            // Apply configuration
            $this->_cacheExpire = $config['basic_settings']['cache_expiration'];
            $this->_thumbSize   = $config['basic_settings']['thumbnail_size'];
            $this->_themeName   = $config['basic_settings']['theme_name'];
            $this->_imgSortBy   = $config['advanced_settings']['images_sort_by'];
            $this->_cacheDir    = $this->_appDir . '/' . $config['advanced_settings']['cache_directory'];
            
            if ($config['basic_settings']['enable_pagination']) {
                $this->_imgPerPage = $config['advanced_settings']['images_per_page'];
            } else {
                $this->_imgPerPage = 0; 
            }
            
        } else {
            $this->setSystemMessage('error', "Unable to read galleryConfig.ini, please make sure the file exists at: <pre>{$configPath}</pre>");
        }

        // Explode working dir and cache dir into arrays
        $workingDirArray = explode('/', getcwd());
        $cacheDirArray   = explode('/', $this->_cacheDir);
        
        // Get largest array count
        $arrayMax = max(count($workingDirArray), count($cacheDirArray));
        
        // Set some default variables
        $diffArray  = array();
        $samePath   = true;
        $key        = 1;
        
        // Generate array of the path differences
        while ($key <= $arrayMax) {
            if (@$cacheDirArray[$key] !== @$workingDirArray[$key] || $samePath !== true) {
                
                // Prepend '..' for every level up that must be traversed
                if (isset($workingDirArray[$key])) {
                    array_unshift($diffArray, '..');
                }
                
                // Append directory name for every directory that must be traversed  
                if (isset($cacheDirArray[$key])) {
                    $diffArray[] = $cacheDirArray[$key];
                } 
                
                // Directory paths have diverged
                $samePath = false;
            }
            
            // Increment key
            $key++;
        }

        // Set the relative thumbnail directory path
        $this->_rThumbsDir = implode('/', $diffArray);

        // Check if cache directory exists and create it if it doesn't
        if (!file_exists($this->_cacheDir)) {
            if (!@mkdir($this->_cacheDir)) {
                $this->setSystemMessage('error', "Unable to create cache dir, please manually create it. Try running <pre>mkdir {$this->_cacheDir}</pre>");
            }
        }
        
        // Check if cache directory is writeable and warn if it isn't
        if(!is_writable($this->_cacheDir)) {
            $this->setSystemMessage('error', "Cache directory needs write permissions. If all else fails, try running: <pre>chmod 777 -R {$this->_cacheDir}</pre>");
        }
    }


    /**
     * Special init method for simple one-line interface.
     * 
     * @access public
     */
    public static function init() {
        $reflection = new ReflectionClass(__CLASS__);
        return $reflection->newInstanceArgs(func_get_args());
    }
    
    
    /**
     * Returns pre-formatted XHTML of a gallery.
     * 
     * @param string $directory Relative path to images directory
     * @param string $relText Text to use as the rel value
     * @access public
     */
    public function createGallery($directory, $relText = 'colorbox') {
        
        $gallery = $this->readImageDirectory($directory);
        
        // Echo formatted gallery markup
        echo '<!-- Start UberGallery ' . UberGallery::VERSION .' - Copyright (c) ' . date('Y') . ' Chris Kankiewicz (http://www.ChrisKankiewicz.com) -->' . PHP_EOL;
        echo '<div id="galleryWrapper">' . PHP_EOL;
        echo '    <ul id="galleryList" class="clearfix">' . PHP_EOL;
        
        foreach ($gallery['images'] as $image) {
            echo "            <li><a href=\"{$image['file_path']}\" title=\"{$image['file_title']}\" rel=\"{$relText}\"><img src=\"{$image['thumb_path']}\" alt=\"{$image['file_title']}\"/></a></li>" . PHP_EOL;
        }
        
        echo '    </ul>' . PHP_EOL;
        echo '    <div id="galleryFooter" class="clearfix">' . PHP_EOL;
        
        if ($gallery['stats']['total_pages'] > 1) {
            echo '        <ul id="galleryPagination">' . PHP_EOL;
            echo "            <li class=\"title\">Page {$gallery['stats']['current_page']} of {$gallery['stats']['total_pages']}</li>" . PHP_EOL;
                
            if ($gallery['stats']['current_page'] > 1) {
                $previousPage = $gallery['stats']['current_page'] - 1;
                echo "                <li><a title=\"Previous Page\" href=\"?page={$previousPage}\">&lt;</a></li>" . PHP_EOL;
            } else {
                echo '                <li class="inactive">&lt;</li>' . PHP_EOL;
            }
                
            for($x = 1; $x <= $gallery['stats']['total_pages']; $x++) {
                if($x == $gallery['stats']['current_page']) {
                    echo "                    <li class=\"current\">{$x}</li>" . PHP_EOL;
                } else {
                    echo "                    <li><a title=\"Page {$x}\" href=\"?page={$x}\">{$x}</a></li>" . PHP_EOL;
                }
            }
                
            if ($gallery['stats']['current_page'] < $gallery['stats']['total_pages']) {
                $nextPage = $gallery['stats']['current_page'] + 1;
                echo "                <li><a title=\"Next Page\" href=\"?page={$nextPage}\">&gt;</a></li>" . PHP_EOL;
            } else {
                echo '                <li class="inactive">&gt;</li>' . PHP_EOL;
            }
            
            echo '        </ul>' . PHP_EOL;
        }
        
        echo '        <div id="credit">Powered by, <a href="http://www.ubergallery.net">UberGallery</a></div>' . PHP_EOL;
        echo '    </div>' . PHP_EOL;
        echo '</div>' . PHP_EOL;
        echo '<!-- End UberGallery - Dual licensed under the MIT & GPL license -->' . PHP_EOL;
        
        return $this;
    }
    
    
    /**
     * Returns an array of files and stats of the specified directory.
     * 
     * @param string $directory Relative path to images directory
     * @access public
     */
    public function readImageDirectory($directory) {
        
        // Set relative image directory
        $this->setRelativeImageDirectory($directory);
        
        // Instantiate gallery array
        $galleryArray = array();
        
        // Return the cached array if it exists and hasn't expired
        if (file_exists($this->_index) && (time() - filemtime($this->_index)) / 60 < $this->_cacheExpire) {
            $galleryArray = $this->_readIndex($this->_index);
        } else {
                
            // Get array of directory
            $dirArray = $this->_readDirectory($directory);
            
            // Loop through array and add additional info
            foreach ($dirArray as $key => $image) {                        
                // Get files relative path
                $relativePath = $this->_rImgDir . '/' . $key;
                
                $galleryArray['images'][htmlentities(pathinfo($image['real_path'], PATHINFO_BASENAME))] = array(
                    'file_title'   => str_replace('_', ' ', pathinfo($image['real_path'], PATHINFO_FILENAME)),
                    'file_path'    => htmlentities($relativePath),
                    'thumb_path'   => $this->_createThumbnail($image['real_path'])
                );
            }
        
            // Add statistics to gallery array
            $galleryArray['stats'] = $this->_readGalleryStats($this->_readDirectory($directory, false));
            
            // Save the sorted array
            $this->_createIndex($galleryArray, $this->_index);
        }
        
        // Return the array
        return $galleryArray;
    }

    /**
     * Returns the theme name.
     * 
     * @access public
     */
    public function getThemeName() {
        // Return the theme name
        return $this->_themeName;
    }
    
    /**
     * Returns the path to the chosen theme directory.
     * 
     * @param bool $absolute Wether or not the path returned is absolute (default = false).
     * @access public
     */
    public function getThemePath($absolute = false) {
        if ($absolute) {
            // Set the theme path
            $themePath = $this->_appDir . '/themes/' . $this->_themeName;
        } else {
            $themePath = 'resources/themes/' . $this->_themeName;
        }
        
        return $themePath;
    }
    
    /**
     * Get an array of error messages or false when empty.
     * 
     * @return array Array of error messages
     * @access public
     */
    public function getSystemMessages() {
        if (isset($this->_systemMessage) && is_array($this->_systemMessage)) {
            return $this->_systemMessage;
        } else {
            return false;
        }
    }
     
    /**
     * Returns XHTML link tag for chosen Colorbox stylesheet.
     * 
     * @param int $themeNum Integer (1-5) representing the Colorbox theme number
     * @return string
     */
    public function getColorboxStyles($themeNum) {
        $path = 'resources/colorbox/' . $themeNum . '/colorbox.css';
        
        return '<link rel="stylesheet" type="text/css" href="' . $path . '" />';
    }

    /**
     * Set cache expiration time in minutes.
     * 
     * @param int $time Cache expiration time in minutes
     * @access public
     */
    public function setCacheExpiration($time) {
        $this->_cacheExpire = $time;
        
        return $this;
    }
    
    
    /**
     * Set the number of images to be displayed per page.
     * 
     * @param int $imgPerPage Number of images to display per page
     * @access public
     */
    public function setImagesPerPage($imgPerPage) {
        $this->_imgPerPage = $imgPerPage;
        
        return $this;
    }
    
    /**
     * Add a message to the system message array
     * 
     * @param string $type The type of message (ie - error, success, notice, etc.)
     * @param string $message The message to be displayed to the user
     * @access public
     */
    public function setSystemMessage($type, $text) {

        // Create empty message array if it doesn't already exist
        if (isset($this->_systemMessage) && !is_array($this->_systemMessage)) {
            $this->_systemMessage = array();
        } 

        // Set the error message
        $this->_systemMessage[] = array(
            'type'  => $type,
            'text'  => $text
        );
        
        return true;
    }
    
    /**
     * Set thumbnail size in pixels.
     * 
     * @param int $size Thumbnail size in pixels.
     * @access public
     */
    public function setThumbSize($size) {
        $this->_thumbSize = $size;
        
        return $this;
    }
    
    
    /**
     * Set the cache directory name.
     * 
     * @param string $directory Cache directory name
     * @access public
     */
    public function setCacheDirectory($directory) {
        $this->_cacheDir = realpath($directory);
        
        return $this;
    }
    
    
    /**
     * Sets the relative path to the image directory.
     * 
     * @param string $directory Relative path to image directory
     * @access public
     */
    public function setRelativeImageDirectory($directory) {
        $this->_imgDir  = realpath($directory);
        $this->_rImgDir = $directory;
        if ($this->_imgPerPage < 1) {
            $this->_index = $this->_cacheDir . '/' . md5($directory) . '-' . 'all.index';
        } else {
            $this->_index = $this->_cacheDir . '/' . md5($directory) . '-' . $this->_page . '.index';            
        }
        
        return $this;
    }
    
    
    /**
     * Reads files in a directory and returns only images.
     * 
     * @param string $directory Path to directory
     * @param boolean $paginate Whether or not paginate the array (default = true)
     * @return array
     * @access protected 
     */
    protected function _readDirectory($directory, $paginate = true) {
        
        // Set index path
        $index = $this->_cacheDir . '/' . md5($directory) . '-' . 'files' . '.index';
        
        // Serve from cache if file exists and caching is enabled e
        if (file_exists($index) && (time() - filemtime($index)) / 60 < $this->_cacheExpire) {
            
            // Read directory array
            $dirArray = $this->_readIndex($index);
            
        } else {
            
            // Loop through directory and add information to array
            if ($handle = opendir($directory)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        
                        // Get files real path
                        $realPath = realpath($directory . '/' . $file);
                        
                        // If file is an image, add info to array
                        if ($this->_isImage($realPath)) {
                            $dirArray[htmlentities(pathinfo($realPath, PATHINFO_BASENAME))] = array(
                                'real_path' => $realPath
                            );
                        }
                    }
                }
                
                // Close open file handle
                closedir($handle);
            }
            
            // Create directory array
            $this->_createIndex($dirArray, $index);
        }
        
        // Set error message if there are no images
        if (!isset($dirArray)) {
            $imageDirectory = realpath($directory);
            $this->setSystemMessage('error', "No images found.  Please upload images to: <pre>{$imageDirectory}</pre>");
        }

        // Sort the array
        $dirArray = $this->_arraySort($dirArray, $this->_imgSortBy);
        
        // Paginate the array and return current page if enabled
        if ($paginate == true && $this->_imgPerPage > 0) {
            $dirArray = $this->_arrayPaginate($dirArray, $this->_imgPerPage, $this->_page);
        }
        
        // Return the array
        return $dirArray;
    }
    
    
    /**
     * Creates a cropped, square thumbnail of given dimensions from a source image,
     * modified from function found on http://www.findmotive.com/tag/php/
     * 
     * @param string $source Path to source image
     * @param int $thumbSize Desired thumbnail size in pixels 
     * @param int $quality Thumbnail quality, applies to JPG and JPEGs only (Value from 1 to 100)
     * @access protected
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
     * Return array from the cached index.
     * 
     * @param string $filePath Path to stored index
     * @return array
     * @access protected
     */
    protected function _readIndex($filePath) {        
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
     * Create serialized index from file array.
     * 
     * @param string $array Array to be indexed
     * @param string $filePath Path to where index will be stored
     * @return boolean
     * @access protected
     */
    protected function _createIndex($array, $filePath) {
        // Serialize array
        $index = fopen($filePath, 'w');
        $serializedArray = serialize($array);
        
        // Write serialized array to index
        if (fwrite($index, $serializedArray)) {
            return true;
        } else {
            return false;
        }
        
    }
    
    
    /**
     * Returns an array of gallery statistics.
     * 
     * @param array $array Array to gather stats from
     * @return array
     * @access protected
     */
    protected function _readGalleryStats($array) {
        // Caclulate total array elements
        $totalElements = count($array);
        
        // Calculate total pages
        if ($this->_imgPerPage > 0) {
            $totalPages = ceil($totalElements / $this->_imgPerPage);
        } else {
            $totalPages = 1;
        }
                
        // Set current page
        if ($this->_page < 1) {
            $currentPage = 1;
        } elseif ($this->_page > $totalPages) {
            $currentPage = $totalPages;
        } else {
            $currentPage = (integer) $this->_page;
        }
        
        // Add stats to array
        $statsArray = array(
            'current_page' => $currentPage,
            'total_images' => $totalElements,
            'total_pages'  => $totalPages
        );
        
        // Return array
        return $statsArray;
    }
    
    
    /**
     * Sorts an array by the provided sort method.
     * 
     * @param array $array Array to be sorted
     * @param string $sort Sorting method (acceptable inputs: natsort, natcasesort, etc.)
     * @return array
     * @access protected
     */
    protected function _arraySort($array, $sortMethod) {
        // Create empty array
        $sortedArray = array();
        
        // Create new array of just the keys and sort it
        $keys = array_keys($array); 
        
        switch ($sortMethod) {
            case 'asort':
                asort($keys);
                break;
            case 'arsort':
                arsort($keys);
                break;
            case 'ksort':
                ksort($keys);
                break;
            case 'krsort':
                krsort($keys);
                break;
            case 'natcasesort':
                natcasesort($keys);
                break;
            case 'natsort':
                natsort($keys);
                break;
            case 'shuffle':
                shuffle($keys);
                break;
        }
        
        // Loop through the sorted values and move over the data
        foreach ($keys as $key) {
            $sortedArray[$key] = $array[$key];
        }
        
        // Return sorted array
        return $sortedArray;
        
    }

    
    /**
     * Paginates array and returns partial array of the current page.
     * 
     * @param string $array Array to be paginated
     * @return array A parial array representing the current page
     * @access protected
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
     * Verifies whether or not a file is an image.
     * 
     * @param string $fileName
     * @return boolean
     * @access protected
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

}

?>
