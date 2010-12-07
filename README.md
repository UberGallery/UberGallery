UberGallery - The simple PHP photo gallery
==========================================
Created by, [Chris Kankiewicz](http://www.chriskankiewicz.com)


Introduction
------------
UberGallery is an easy to use, simple to manage, web photo gallery written in PHP.

UberGallery features include:

* Simple first time installation
* Databaseless configuration
* Create multiple galleries with a single installation


Requirements
------------
UberGallery requires PHP 5.0+ and the PHP-GD image library to work properly.


Simple Installation
-------------------
1. Upload `index.php`, `resources/` and `images/` to your web server where you would like the gallery to be displayed.
2. Upload your images to the `images/` directory.
3. Make the `resources/cache/` directory writable by the web server:
    
    `chmod 777 -R /path/to/resources/cache`
    
4. Open your web browser and navigate to the directory where you installed UberGallery to have the script generate thumbnails and display your images.


Install to Pre-Existing Web Page
--------------------------------
1. Upload the `resources` folder to your web server.
2. Insert the following code into the PHP page where you would like the gallery to be displayed (be sure to change the include and image folder path match your configuration):
    
    ``<?php include_once('path/to/resources/UberGallery.php'); $gallery = UberGallery::init()->createGallery('path/to/images-folder'); ?>``
    
3. Copy `resources/css/ubergallery.css` to your CSS directory and include it in your page:
    
    ``<link rel="stylesheet" type="text/css" href="path/to/styles/ubergallery.css" />``
    
4. Upload your images to your images directory.
5. Make the `resources/cache/` directory writable by the web server.
    
    `chmod 777 -R /path/to/resources/cache`
    
6. Open your web browser and navigate to the directory where you installed UberGallery to have the script generate thumbnails and display your images.


License
-------
UberGallery is dual licensed under the terms of the [MIT License](http://www.opensource.org/licenses/mit-license.php) and [GNU General Public License (GPL) Version 3](http://www.gnu.org/licenses/gpl.txt). See `COPYING-MIT` and `COPYING-GPL` for details.
