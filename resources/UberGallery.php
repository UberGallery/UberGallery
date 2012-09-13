<?php

/**
 * UberGallery is an easy to use, simple to manage, web photo gallery written in
 * PHP. UberGallery does not require a database and supports JPEG, GIF and PNG 
 * file types. Simply upload your images and UberGallery will automatically 
 * generate thumbnails and output standards complaint XHTML markup on the fly.
 * 
 * This software is distributed under the MIT License
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * More info available at http://www.ubergallery.net
 * 
 * @author Chris Kankiewicz (http://www.chriskankiewicz.com)
 * @copyright 2012 Chris Kankiewicz
 */
class UberGallery {
    
    // Define application version
    const VERSION = '2.4.1';
    
    // Reserve some variables
    protected $_config     = array();
    protected $_imgDir     = NULL;
    protected $_appDir     = NULL;
    protected $_index      = NULL;
    protected $_rThumbsDir = NULL;
    protected $_rImgDir    = NULL;
    protected $_now        = NULL;
    
    
    /**
     * UberGallery construct function. Runs on object creation.
     */
    public function __construct() {
        
        // Get timestamp for the current time
        $this->_now = time();
        
        // Sanitize input and set current page
        if (isset($_GET['page'])) {
            $this->_page = (integer) $_GET['page'];
        } else {
            $this->_page = 1;
        }
        
        // Set class directory constant
        if(!defined('__DIR__')) {
            define('__DIR__', dirname(__FILE__));
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
            $this->setCacheExpiration($config['basic_settings']['cache_expiration']);
            $this->setPaginatorThreshold($config['basic_settings']['paginator_threshold']);
            $this->setThumbSize($config['basic_settings']['thumbnail_width'], $config['basic_settings']['thumbnail_height']);
            $this->setThumbQuality($config['basic_settings']['thumbnail_quality']);
            $this->setThemeName($config['basic_settings']['theme_name']);
            $this->setSortMethod($config['advanced_settings']['images_sort_by'], $config['advanced_settings']['reverse_sort']);
            $this->setDebugging($config['advanced_settings']['enable_debugging']);
            $this->setCacheDirectory($this->_appDir . '/cache');
            
            if ($config['basic_settings']['enable_pagination']) {
                $this->setImagesPerPage($config['advanced_settings']['images_per_page']);
            } else {
                $this->setImagesPerPage(0); 
            }
            
        } else {
            die("Unable to read galleryConfig.ini, please make sure the file exists at: <pre>{$configPath}</pre>");
        }

        // Get the relative thumbs directory path
        $this->_rThumbsDir = $this->_getRelativePath(getcwd(), $this->_config['cache_dir']);
        
        // Check if cache directory exists and create it if it doesn't
        if (!file_exists($this->_config['cache_dir'])) {
            $this->setSystemMessage('error', "Cache directory does not exist, please manually create it.");
        }
        
        // Check if cache directory is writeable and warn if it isn't
        if (!is_writable($this->_config['cache_dir'])) {
            $this->setSystemMessage('error', "Cache directory needs write permissions. If all else fails, try running: <pre>chmod 777 {$this->_config['cache_dir']}</pre>");
        }
        
        // Set debug log path
        $this->_debugLog = $this->_config['cache_dir'] . '/debug.log';
        
        // Set up debugging if enabled
        if ($this->_config['debugging']) {
            
            // Initialize log if it doesn't exist
            if (!file_exists($this->_debugLog)) {
                
                // Get libgd info
                $gd = gd_info();
                
                // Get system and package info
                $timestamp  = date('Y-m-d H:i:s');
                $ugVersion  = 'UberGallery v' . UberGallery::VERSION;
                $phpVersion = 'PHP: ' . phpversion();
                $gdVersion  = 'GD: ' . $gd['GD Version'];
                $osVersion  = 'OS: ' . PHP_OS;
                
                // Combine all the things!
                $initText = $timestamp . ' / ' . $ugVersion . ' / ' . $phpVersion . ' / ' . $gdVersion . ' / ' . $osVersion . PHP_EOL;
                
                // Create file with initilization text
                file_put_contents($this->_debugLog, $initText, FILE_APPEND);
            }
            
            // Set new error handler
            set_error_handler("UberGallery::_errorHandler");
            
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

        // Get the gallery data array and set the template path         
        $galleryArray = $this->readImageDirectory($directory);
        $templatePath = $this->_appDir . '/templates/default.php';
        
        // Set the relative text attribute
        $galleryArray['relText'] = $relText;
        
        // Echo the template contents
        echo $this->_readTemplate($templatePath, $galleryArray);
        
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
        
        // Get the cached array 
        $galleryArray = $this->_readIndex($this->_index);
        
        // If cached array is false, read the directory
        if (!$galleryArray) {
                
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
            
            // Add gallery paginator to the gallery array
            $galleryArray['paginator'] = $this->_getPaginatorArray($galleryArray['stats']['current_page'], $galleryArray['stats']['total_pages']);
            
            // Save the sorted array
            if ($this->_config['cache_expire'] > 0) {
                $this->_createIndex($galleryArray, $this->_index);
            }
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
        return $this->_config['theme_name'];
    }
    
    
    /**
     * Returns the path to the chosen theme directory.
     * 
     * @param bool $absolute true = return absolute path / false = return relative path (default)
     * @access public
     */
    public function getThemePath($absolute = false) {
        if ($absolute) {
            // Set the theme path
            $themePath = $this->_appDir . '/themes/' . $this->_config['theme_name'];
        } else {
            // Get relative path to application dir
            $realtivePath = $this->_getRelativePath(getcwd(), $this->_appDir);
            
            // Set the theme path
            $themePath = $realtivePath . '/themes/' . $this->_config['theme_name'];
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
        
        // Get relative path to application dir
        $realtivePath = $this->_getRelativePath(getcwd(), $this->_appDir);
        
        // Set Colorbox Path
        $colorboxPath = $realtivePath . '/colorbox/' . $themeNum . '/colorbox.css';
        
        return '<link rel="stylesheet" type="text/css" href="' . $colorboxPath . '" />';
    }


    /**
     * Set cache expiration time in minutes.
     * 
     * @param int $time Cache expiration time in minutes
     * @access public
     */
    public function setCacheExpiration($time = 0) {
        $this->_config['cache_expire'] = $time;
        
        return $this;
    }
    
    
    /**
     * Set the number of images to be displayed per page.
     * 
     * @param int $imgPerPage Number of images to display per page
     * @access public
     */
    public function setImagesPerPage($imgPerPage = 0) {
        $this->_config['img_per_page'] = $imgPerPage;
        
        return $this;
    }

    
    /**
     * Set thumbnail size in pixels.
     * 
     * @param int $size Thumbnail size in pixels
     * @access public
     */
    public function setThumbSize($width = 100, $height = 100) {
        $this->_config['thumbnail']['width'] = $width;
        $this->_config['thumbnail']['height'] = $height;
        
        return $this;
    }

    
    /**
     * Set thumbnail quality as a value from 1 - 100.
     * This only affects JPEGs and has no effect on GIF or PNGs.
     * 
     * @param int $quality Thumbnail size in pixels
     * @access public
     */
    public function setThumbQuality($quality = 75) {
        $this->_config['thumbnail']['quality'] = $quality;
        
        return $this;
    }
    
    
    /**
     * Set theme name.
     * 
     * @param string $name Theme name
     * @access public
     */
    public function setThemeName($name = 'uber-blue') {
        $this->_config['theme_name'] = $name;
        
        return $this;
    }
    
    
    /**
     * Set the sortting method.
     * 
     * @param string $method Sorting method
     * @access public
     */
    public function setSortMethod($method = 'natcasesort', $reverse = false) {
        $this->_config['sort_method']  = $method;
        $this->_config['reverse_sort'] = $reverse;        
        
        return $this;
    }
    
    
    /**
     * Enable or disable debugging.
     * 
     * @param boolean $bool true = on / false = off
     * @access public
     */
    public function setDebugging($bool = false) {
        $this->_config['debugging'] = $bool;
        
        return $this;
    }
    
    
    /**
     * Set the cache directory name.
     * 
     * @param string $directory Cache directory name
     * @access public
     */
    public function setCacheDirectory($directory) {
        $this->_config['cache_dir'] = realpath($directory);
        
        return $this;
    }
    
    
    /**
     * Set the paginator threshold.
     * 
     * @param int $threshold Paginator threshold value
     * @access public
     */
    public function setPaginatorThreshold($threshold = 10) {
        $this->_config['threshold'] = $threshold;
        
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
        if ($this->_config['img_per_page'] < 1) {
            $this->_index = $this->_config['cache_dir'] . '/' . md5($directory) . '-' . 'all.index';
        } else {
            $this->_index = $this->_config['cache_dir'] . '/' . md5($directory) . '-' . $this->_page . '.index';            
        }
        
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
     * Reads files in a directory and returns only images.
     * 
     * @param string $directory Path to directory
     * @param boolean $paginate Whether or not paginate the array (default = true)
     * @return array
     * @access private 
     */
    private function _readDirectory($directory, $paginate = true) {
        
        // Set index path
        $index = $this->_config['cache_dir'] . '/' . md5($directory) . '-' . 'files' . '.index';
        
        // Read directory array
        $dirArray = $this->_readIndex($index);
        
        // Serve from cache if file exists and caching is enabled e
        if (!$dirArray) {
            
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
            if ($this->_config['cache_expire'] > 0) {
                $this->_createIndex($dirArray, $index);
            }
        }
        
        // Set error message if there are no images
        if (!isset($dirArray)) {
            $imageDirectory = realpath($directory);
            $this->setSystemMessage('error', "No images found.  Please upload images to: <pre>{$imageDirectory}</pre>");
        }

        // Sort the array
        $dirArray = $this->_arraySort($dirArray, $this->_config['sort_method'], $this->_config['reverse_sort']);
        
        // Paginate the array and return current page if enabled
        if ($paginate == true && $this->_config['img_per_page'] > 0) {
            $dirArray = $this->_arrayPaginate($dirArray, $this->_config['img_per_page'], $this->_page);
        }
        
        // Return the array
        return $dirArray;
    }


    /**
     * Returns a template string with custom data injected into it. 
     * 
     * @param string $templatePath Path to template file
     * @param array $data Array of data to be injected into the template
     * @return string Processed template string
     * @access private 
     */
    private function _readTemplate($templatePath, $data) {
        
        // Extract array to variables
        extract($data);
        
        // Start the output buffer
        ob_start();
        
        // Include the template
        include $templatePath;
        
        // Set buffer output to a variable
        $output = ob_get_clean();
        
        // Return the output
        return $output;
        
    }
    
    
    /**
     * Creates a cropped, square thumbnail of given dimensions from a source image,
     * modified from function found on http://www.findmotive.com/tag/php/
     * 
     * @param string $source Path to source image
     * @param int $thumbWidth Desired thumbnail width size in pixels
     * @param int $thumbHeight Desired thumbnail height size in pixels
     * @param int $quality Thumbnail quality, applies to JPG and JPEGs only (Value from 1 to 100)
     * @access private
     */
    private function _createThumbnail($source, $thumbWidth = NULL, $thumbHeight = NULL, $quality = NULL) {
        
        // Set defaults thumbnail width if not specified
        if ($thumbWidth === NULL) {
            $thumbWidth = $this->_config['thumbnail']['width'];
        }
        
        // Set defaults thumbnail height if not specified
        if ($thumbHeight === NULL) {
            $thumbHeight = $this->_config['thumbnail']['height'];
        }
        
        // Set defaults thumbnail height if not specified
        if ($quality === NULL) {
            $quality = $this->_config['thumbnail']['quality'];
        }
        
        // MD5 hash of source image path
        $fileHash = md5($source);
        
        // Get file extension from source image
        $fileExtension = pathinfo($source, PATHINFO_EXTENSION);
        
        // Build file name
        $fileName = $thumbWidth . 'x' . $thumbHeight . '-' . $quality . '-' . $fileHash . '.' . $fileExtension;
        
        // Build thumbnail destination path
        $destination = $this->_config['cache_dir'] . '/' . $fileName;
        
        // If file is cached return relative path to thumbnail
        if ($this->_isFileCached($destination)) {
            $relativePath = $this->_rThumbsDir . '/' . $fileName;
            return $relativePath;
        }
        
        // Get needed image information
        $imgInfo = getimagesize($source);
        $width   = $imgInfo[0];
        $height  = $imgInfo[1];
        $x       = 0;
        $y       = 0;
        
        // Calculate ratios
        $srcRatio   = $width / $height;
        $thumbRatio = $thumbWidth / $thumbHeight;
        
        if ($srcRatio > $thumbRatio) {
            
            // Preserver original width
            $originalWidth = $width;
            
            // Crop image width to proper ratio
            $width = $height * $thumbRatio;
            
            // Set thumbnail x offset
            $x = ceil(($originalWidth - $width) / 2);
            
        } elseif ($srcRatio < $thumbRatio) {
            
            // Preserver original height
            $originalHeight = $height;
            
            // Crop image height to proper ratio
            $height = ($width / $thumbRatio);
            
            // Set thumbnail y offset
            $y = ceil(($originalHeight - $height) / 2);
            
        }

        // Create new empty image of proper dimensions
        $newImage = imagecreatetruecolor($thumbWidth, $thumbHeight);

        // Create new thumbnail
        if ($imgInfo[2] == IMAGETYPE_JPEG) {
            $image = imagecreatefromjpeg($source);
            imagecopyresampled($newImage, $image, 0, 0, $x, $y, $thumbWidth, $thumbHeight, $width, $height);
            imagejpeg($newImage, $destination, $quality);
        } elseif ($imgInfo[2] == IMAGETYPE_GIF) {
            $image = imagecreatefromgif($source);
            imagecopyresampled($newImage, $image, 0, 0, $x, $y, $thumbWidth, $thumbHeight, $width, $height);
            imagegif($newImage, $destination);
        } elseif ($imgInfo[2] == IMAGETYPE_PNG) {
            $image = imagecreatefrompng($source);
            imagecopyresampled($newImage, $image, 0, 0, $x, $y, $thumbWidth, $thumbHeight, $width, $height);
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
     * @access private
     */
    private function _readIndex($filePath) {
        
        // Return false if file doesn't exist or the cache has expired
        if (!$this->_isFileCached($filePath)) {
            return false;
        }
        
        // Read file index
        $indexString = file_get_contents($filePath);
        
        // Unsearialize the array
        $indexArray = unserialize($indexString);
        
        // Decode the array
        $decodedArray = $this->_arrayDecode($indexArray);
        
        // Return the array
        return $decodedArray;
    }
    

    /**
     * Create serialized index from file array.
     * 
     * @param string $array Array to be indexed
     * @param string $filePath Path to where index will be stored
     * @return boolean
     * @access private
     */
    private function _createIndex($array, $filePath) {
        
        // Encode the array
        $encodedArray = $this->_arrayEncode($array);
        
        // Serialize array
        $serializedArray = serialize($encodedArray);
        
        // Write serialized array to index
        if (file_put_contents($filePath, $serializedArray)) {
            return true;
        }
        
        return false;
        
    }
    
    /**
     * Runs all array strings through base64_encode.
     * This prevents errors with non-English languages.
     * 
     * @param array $array Array to be encoded
     * @return array
     * @access private
     */
    private function _arrayEncode($array) {
        
        $encodedArray = array();
        
        foreach ($array as $key => $item) {
            
            // Base64 encode the array keys
            $key = base64_encode($key);
            
            // Base64 encode the array values
            if (is_array($item)) {
                    
                // Recursively call _arrayEncode()
                $encodedArray[$key] = $this->_arrayEncode($item);
                
            } elseif (is_string($item)) {
                
                // Base64 encode the string
                $encodedArray[$key] = base64_encode($item);
                
            } else {
                
                // Pass value unaltered to new array
                $encodedArray[$key] = $item;
                
            }
        }
        
        // Return the encoded array
        return $encodedArray;
        
    }
    
    
    /**
     * Decodes an encoded array.
     * 
     * @param array $array Array to be decoded
     * @return array
     * @access private
     */
    private function _arrayDecode($array) {
        
        $decodedArray = array();
        
        foreach ($array as $key => $item) {
            
            // Base64 decode the array keys
            $key = base64_decode($key);
            
            // Base64 decode the array values
            if (is_array($item)) {
                    
                // Recursively call _arrayDecode()
                $decodedArray[$key] = $this->_arrayDecode($item);
                
            } elseif (is_string($item)) {
                
                // Base64 decode the string
                $decodedArray[$key] = base64_decode($item);
                
            } else {
                
                // Pass value unaltered to new array
                $decodedArray[$key] = $item;
                
            }
        }
        
        // Return the decoded array
        return $decodedArray;
        
    }
    
    
    /**
     * Returns an array of gallery statistics.
     * 
     * @param array $array Array to gather stats from
     * @return array
     * @access private
     */
    private function _readGalleryStats($array) {
        // Caclulate total array elements
        $totalElements = count($array);
        
        // Calculate total pages
        if ($this->_config['img_per_page'] > 0) {
            $totalPages = ceil($totalElements / $this->_config['img_per_page']);
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
     * Returns a formatted array for the gallery paginator.
     * 
     * @param int $currentPage The current page being viewed
     * @param int $totalPages Total number of pages in the gallery
     * @return array
     * @access private
     */
    private function _getPaginatorArray($currentPage, $totalPages) {
        
        // Set some variables
        $range     = ceil($this->_config['threshold'] / 2) - 1;
        $firstPage = $currentPage - $range;
        $lastPage  = $currentPage + $range;
        $firstDiff = NULL;
        $lastDiff  = NULL;

        // Ensure first page is within the bounds of available pages
        if ($firstPage <= 1) {
            $firstDiff = 1 - $firstPage;
            $firstPage = 1;
        }
        
        // Ensure last page is within the bounds of available pages
        if ($lastPage >= $totalPages) {
            $lastDiff = $lastPage - $totalPages;
            $lastPage = $totalPages;
        }
        
        // Apply page differences
        $lastPage  = $lastPage + $firstDiff;
        $firstPage = $firstPage - $lastDiff;
        
        // Recheck first and last page to ensure they're within proper bounds
        if ($firstPage <= 1 && $lastPage >= $totalPages) {
            $firstPage = 1;
            $lastPage  = $totalPages;
        }
        
        // Create title element
        $paginatorArray[] = array(
            'text'  => 'Page ' . $currentPage . ' of ' . $totalPages,
            'class' => 'title'
        );
        
        // Create previous page element
        if ($currentPage == 1) {
            
            $paginatorArray[] = array(
                'text'  => '&lt;',
                'class' => 'inactive'
            );
            
        } else {
            
            $paginatorArray[] = array(
                'text'  => '&lt;',
                'class' => 'active',
                'href'  => '?page=' . ($currentPage - 1)
            );
            
        }
        
        // Set previous overflow
        if ($firstPage > 1) {
            $paginatorArray[] = array(
                'text'  => '...',
                'class' => 'more',
                'href'  => '?page=' . ($currentPage - $range - 1)
            );
        }
        
        // Generate the page elelments
        for ($i = $firstPage; $i <= $lastPage; $i++) {
            
            if ($i == $currentPage) {
                
                $paginatorArray[] = array(
                    'text'  => $i,
                    'class' => 'current'
                );
                
            } else {
                
                $paginatorArray[] = array(
                    'text'  => $i,
                    'class' => 'active',
                    'href'  => '?page=' . $i
                );
                
            }
            
        }
        
        // Set next overflow 
        if ($lastPage < $totalPages) {
            $paginatorArray[] = array(
                'text'  => '...',
                'class' => 'more',
                'href'  => '?page=' . ($currentPage + $range + 1)
            );
        }
        
        // Create next page element
        if ($currentPage == $totalPages) {
            
            $paginatorArray[] = array(
                'text'  => '&gt;',
                'class' => 'inactive'
            );
            
        } else {
            
            $paginatorArray[] = array(
                'text'  => '&gt;',
                'class' => 'active',
                'href'  => '?page=' . ($currentPage + 1)
            );
            
        }
        
        // Return the paginator array
        return $paginatorArray;
        
    }
    
    
    /**
     * Sorts an array by the provided sort method.
     * 
     * @param array $array Array to be sorted
     * @param string $sort Sorting method (acceptable inputs: natsort, natcasesort, etc.)
     * @return array
     * @access private
     */
    private function _arraySort($array, $sortMethod, $reverse = false) {
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
        
        // Reverse array if set
        if ($reverse) {
            $sortedArray = array_reverse($sortedArray, true);
        }
        
        // Return sorted array
        return $sortedArray;
        
    }

    
    /**
     * Paginates array and returns partial array of the current page.
     * 
     * @param string $array Array to be paginated
     * @return array A parial array representing the current page
     * @access private
     */
    private function _arrayPaginate($array, $resultsPerPage, $currentPage) {
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
     * @access private
     */
    private function _isImage($filePath) {
        // Get file type
        if (function_exists('exif_imagetype')) {
            $imgType = @exif_imagetype($filePath);
        } else {
            $imgArray = @getimagesize($filePath);
            $imgType = $imgArray[2];
        }

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
     * Compares two paths and returns the relative path from one to the other
     * 
     * @param string $fromPath Starting path
     * @param string $toPath Ending path
     * @return string $relativePath
     * @access private
     */
    private function _getRelativePath($fromPath, $toPath) {
        
        // Define the OS specific directory separator
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        
        // Remove double slashes from path strings
        $fromPath   = str_replace(DS . DS, DS, $fromPath);
        $toPath     = str_replace(DS . DS, DS, $toPath);
        
        // Explode working dir and cache dir into arrays
        $fromPathArray  = explode(DS, $fromPath);
        $toPathArray    = explode(DS, $toPath);
        
        // Remove last fromPath array element if it's empty
        $x = count($fromPathArray) - 1;
        
        if(!trim($fromPathArray[$x])) {
            array_pop($fromPathArray);
        }
        
        // Remove last toPath array element if it's empty
        $x = count($toPathArray) - 1;
        
        if(!trim($toPathArray[$x])) {
            array_pop($toPathArray);
        }
        
        // Get largest array count
        $arrayMax = max(count($fromPathArray), count($toPathArray));
        
        // Set some default variables
        $diffArray  = array();
        $samePath   = true;
        $key        = 1;
        
        // Generate array of the path differences
        while ($key <= $arrayMax) {
            
            // Get to path variable
            $toPath = isset($toPathArray[$key]) ? $toPathArray[$key] : NULL;
            
            // Get from path variable
            $fromPath = isset($fromPathArray[$key]) ? $fromPathArray[$key] : NULL;
            
            if ($toPath !== $fromPath || $samePath !== true) {
                
                // Prepend '..' for every level up that must be traversed
                if (isset($fromPathArray[$key])) {
                    array_unshift($diffArray, '..');
                }
                
                // Append directory name for every directory that must be traversed  
                if (isset($toPathArray[$key])) {
                    $diffArray[] = $toPathArray[$key];
                } 
                
                // Directory paths have diverged
                $samePath = false;
            }
            
            // Increment key
            $key++;
        }

        // Set the relative thumbnail directory path
        $relativePath = implode('/', $diffArray);
        
        // Return the relative path
        return $relativePath;
        
    }


    /**
     * Determines if a file is cached or not
     * 
     * @param string $filePath Path to file to check
     * @return bool Returns true if file is cached and available, false if not
     * @access private
     */
    private function _isFileCached($filePath) {
        
        if (file_exists($filePath) && ($this->_now - filemtime($filePath)) / 60 <= $this->_config['cache_expire']) {
            return true;
        }
        
        return false;
        
    }
    
    
    /**
     * Custom error handler for logging errors to the debug log
     * 
     * @param int $errorNum Level of the error raised
     * @param string $errorMsg The error message 
     * @param string $fileName Filename that the error was raised in
     * @param int $lineNum Line number the error was raised at
     * @param array $vars Array pointing to the active symbol table at the point the error occurred
     * @access private
     */
    private function _errorHandler($errorNum, $errorMsg, $fileName, $lineNum, $vars) {
        
        // Set current timestamp
        $time = date('Y-m-d H:i:s');
        
        // Build error type array
        $errorType = array (
            1    => "Error",
            2    => "Warning",
            4    => "Parsing Error",
            8    => "Notice",
            16   => "Core Error",
            32   => "Core Warning",
            64   => "Compile Error",
            128  => "Compile Warning",
            256  => "User Error",
            512  => "User Warning",
            1024 => "User Notice"
        );
        
        // Set error type
        $errorLevel = $errorType[$errorNum];
        
        // Build the log message text
        $logMessage  = $time . ' : ' . $fileName . ' on line '. $lineNum . ' [' . $errorLevel . '] ' . $errorMsg . PHP_EOL;
        
        // Append the message to the log
        if ($errorNum != 8) {
            error_log($logMessage, 3, $this->_debugLog, FILE_APPEND);
        }
        
        // Terminate on fatal error
        if ($errorNum != 2 && $errorNum != 8) {
            die("A fatal error has occurred, script execution aborted. See debug.log for more info.");
        }
        
    }

}

?>
