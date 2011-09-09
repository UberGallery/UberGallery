<?php
    
    // Include the UberGallery class
    include_once('resources/UberGallery.php');
    
    // Initialize the UberGallery object
    $gallery = new UberGallery();
    
    // Initialize the gallery array
    $galleryArray = $gallery->readImageDirectory('gallery-images');

    // Define theme path
    define('THEMEPATH', $gallery->getThemePath());

    // Set path to theme index    
    $themeIndex = THEMEPATH . '/index.php';
    
    // Initialize the theme
    if (file_exists($themeIndex)) {
        include($themeIndex);
    } else {
        die('ERROR: Failed to initialize theme');
    }

?>