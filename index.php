<?php
    
    // Include the UberGallery class
    include_once('resources/UberGallery.php');
    
    // Initialize the UberGallery
    $gallery = new UberGallery();
    
    // Initialize the gallery array
    $galleryArray = $gallery->readImageDirectory('gallery-images');

    // Set path to theme index    
    $themeIndex = $gallery->getThemePath(false) . '/index.php';
    
    // Initialize the theme
    if (file_exists($themeIndex)) {
        include($gallery->getThemePath(false) . '/index.php');
    } else {
        die('ERROR: Failed to initialize theme');
    }

?>