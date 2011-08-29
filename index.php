<?php
    
    // Include the UberGallery class
    include_once('resources/UberGallery.php');
    
    // Initialize the UberGallery
    $gallery = new UberGallery();
    
    // Initialize the gallery array
    $galleryArray = $gallery->readImageDirectory('gallery-images');
    
    // Initialize the theme
    include($gallery->getThemePath(false) . '/index.php');
    
    // Set the THEMEPATH constant
    // const THEMEPATH = $gallery->getThemePath();
    
?>