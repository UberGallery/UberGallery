<?php

    require('vendor/autoload.php');

    $gallery = new Uber\Gallery(['images'], 'config/gallery.php');

    print_r($gallery); die();
