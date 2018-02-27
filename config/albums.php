<?php

/**
 * This file contains the configuration for your albums. You may define an array
 * of albums where each key is the album directory name (as it is in the albums
 * directory) and the value is an array of album-specific options.
 */

return [

    'default' => [

        /**
         * Give your album a friendly title.
         *
         * Default value: 'Default Album'
         */
        'title' => 'Default Album',

        /**
         * Set the image you would like to use as the album thumnail. If ommited
         * or set it to null the first image of the album will be used.
         *
         * Default value: null
         */
        'thumbnail' => null,

        /**
         * Configure this album's thumbnail options.
         */
        'thumbnails' => [
            /**
             * The maximum width of your thumbnails in pixels.
             *
             * Default value: 480
             */
            'width' => 480,

            /**
             * The maximum height of your thumbnails in pixels.
             *
             * Default value: 480
             */
            'height' => 480,

            /**
             * TODO: Add a way to override the resize method
             * Override the thumbnail resizing method. Available options are:
             *   'crop' - Scale and crop to the exact dimensions specified
             *   'fit' - Keep the image dimensions but fit within specified dimensions
             *   'force' - Squish the image to the specified dimensions without cropping
             *
             * Default value: 'fit'
             */
            'resize' => 'fit'
        ],

        /**
         * Override the default album path by providing an absolute path to the
         * album directory. Example: '/var/www/gallrey/albums/cats'
         *
         * Default value: null
         */
        'path' => null,

    ]

];
