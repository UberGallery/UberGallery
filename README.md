UberGallery - The simple PHP photo gallery
==========================================
Created by, [Chris Kankiewicz](http://www.ChrisKankiewicz.com)


Introduction
------------
UberGallery is an easy to use, simple to manage, web photo gallery written in PHP and distributed
under the [MIT License](http://www.opensource.org/licenses/mit-license.php). UberGallery
**does not** require a database and supports JPEG, GIF and PNG file types. Simply upload your images
and UberGallery will automatically generate thumbnails and output standards compliant XHTML markup
on the fly.


Features
--------
  * Simple first time installation
  * Database-less configuration
  * Include galleries within pre-existing sites
  * Create multiple galleries with a single installation
  * Easily customize your gallery styles via CSS
  * Install and update the gallery easily wth Git (optional)


Requirements
------------
UberGallery requires PHP 5.2+ and the PHP-GD image library to work properly. For more information on
PHP and the PHP-GD image library, please visit [http://php.net](http://php.net).


Simple Installation
-------------------
  1. Copy `resources/sample.galleryConfig.ini` to `resources/galleryConfig.ini` and modify the settings
to your liking.

  2. Upload `index.php`, `resources/` and `gallery-images/` to your web server.

  3. Upload images to the `gallery-images/` directory.

  4. Make the `resources/cache/` directory writable by the web server:

    ```
    chmod 777 /path/to/resources/cache
    ```

  5. Open your web browser and load the page where you installed UberGallery.


Custom Installation
-------------------
  1. Copy `resources/sample.galleryConfig.ini` to `resources/galleryConfig.ini` and modify the settings
to your liking.

  2. Upload the `resources/` folder to your web server.

  3. Insert the following code into the PHP page where you would like the gallery to be displayed
(be sure to change the include and image folder path to match your configuration):

    ```php
    <?php include_once('path/to/resources/UberGallery.php'); $gallery = UberGallery::init()->createGallery('path/to/images-folder'); ?>
    ```

  4. Include the UberGallery and desired Colorbox style sheet in your page header:

    ```html
    <link rel="stylesheet" type="text/css" href="path/to/resources/UberGallery.css" />
    <link rel="stylesheet" type="text/css" href="path/to/resources/colorbox/{1-5}/colorbox.css" />
    ```
    
  5. Include the jQuery and Colorbox javascript files in your page header:

    ```html
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script type="text/javascript" src="path/to/resources/colorbox/jquery.colorbox.js"></script>
    ```

  6. Include the Colorbox jquery call in your header:

    ```html
    <script type="text/javascript">
    $(document).ready(function(){
        $("a[rel='colorbox']").colorbox({maxWidth: "90%", maxHeight: "90%", opacity: ".5"});
    });
    </script>
    ```

  7. Upload images to your images directory.

  8. Make the `resources/cache/` directory writable by the web server.

    ```
    chmod 777 /path/to/resources/cache
    ```

  9. Open your web browser and load the page where you installed UberGallery.


Install with Git
----------------
  1. SSH into the your server and clone the UberGallery repository and submodules:

    ```
    git clone --recursive git://github.com/UberGallery/UberGallery.git /path/to/gallery-directory
    ```

  2. CD to your UberGallery installation:

    ```
    cd /path/to/gallery-directory
    ```

  3. Copy `resources/sample.galleryConfig.ini` to `resources/galleryConfig.ini` and modify the settings

    ```
    cp resource/sample.galleryConfig.ini resources/galleryConfig.ini
    nano resources/galleryConfig.ini
    ```

  4. Make the `resources/cache/` directory writable by the web server.

    ```
    chmod 777 resources/cache
    ```

  5. Upload images to the `gallery-images/` folder within your gallery directory.

  6. Open your web browser and load the page where you installed UberGallery.

**NOTE:** When using this method to install UberGallery, you may update your installation by running
the following commands:

    cd /path/to/gallery-directory
    git pull origin master
    git submodule update


Troubleshooting
---------------
If you're having issues with UberGallery here are a few troubleshooting tips:

  * Verify that you have PHP 5.2 or later installed.  You can verify your PHP version by running:

    ```
    php --version
    ```

  * Make sure you have the latest version of UberGallery installed.  You can always find the latest
    version at <http://www.ubergallery.net/#download>

  * Replace your `galleryConfig.ini` with `sample.galleryConfig.ini` to ensure proper configuration:

    ```
    rm resources/galleryConfig.ini
    cp resource/sample.galleryConfig.ini resources/galleryConfig.ini
    ```

  * Clear your cache and make sure the directory is writable by the web server:

    ```
    rm -f resources/cache/*
    chmod 777 resources/cache
    ```

  * Enable debugging by setting the `enable_debugging` option in `resources/galleryConfig.ini` to
    `true`, try loading your gallery in a web browser then inspect the debug.log file in your cache
    directory for any errors.

If you continue to have issues, please email me at: <Chris@ChrisKankiewicz.com>


News & Updates
--------------
UberGallery updates and news can be found on our [blog](http://news.ubergallery.net/) or by
[following us on Twitter](http://twitter.com/ubergallery).

Please report bugs to the [Github issue tracker](http://github.com/UberGallery/ubergallery/issues).


License
-------
UberGallery is distributed under the terms of the
[MIT License](http://www.opensource.org/licenses/mit-license.php).
Copyright © 2012 [Chris Kankiewicz](http://www.chriskankiewicz.com)
