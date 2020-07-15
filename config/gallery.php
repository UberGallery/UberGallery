<?php

use App\Support\Helpers;

return [
    /*
     * The title of your gallery.
     *
     * Default value: 'Uber Gallery'
     */
    'gallery_title' => Helpers::env('GALLERY_TITLE', 'Uber Gallery'),

    /*
     * Maximum width of your thumbnails in pixels.
     *
     * Default value: 480
     */
    'thumbnail_width' => Helpers::env('THUMBNAIL_WIDTH', 480),

    /*
     * Maximum height of your thumbnails in pixels.
     *
     * Default value: 480
     */
    'thumbnail_height' => Helpers::env('THUMBNAIL_HEIGHT', 480),

    /*
     * Thumbnail resizing method. Available options are:
     *
     *   'fit' - Keep the image aspect ratio but fit within specified dimensions
     *   'crop' - Destructively scale and crop to the exact dimensions specified
     *   'force' - Destructively scale the image to the specified dimensions without cropping
     *
     * Default value: 'fit'
     */
    'thumbnail_resize' => Helpers::env('THUMBNAIL_RESIZE', 'fit'),

    /*
     * Thumnail quality as a value between 1 and 100. The higher the value the
     * better quality but also slower and more resource intensive.
     *
     * Default value: 85
     */
    'thumbnail_quality' => Helpers::env('THUMBNAIL_QUALITY', 85),

    /*
     * If your album contains many images you may wish to enable pagination
     * to split up the album into several smaller pages of images.
     *
     * Default value: false
     */
    'pagination' => Helpers::env('PAGINATION', false),

    /*
     * If pagination is enabled this is how many image will be shown per page.
     *
     * Default value: 24
     */
    'images_per_page' => Helpers::env('IMAGES_PER_PAGE', 24),

    /*
     * You may specify the sorting method used by setting one of the
     * possible values or, for more advanced sorting you can pass a
     * custom algorithm via a closure. Refer to the documentation for
     * `uasort` (the method used under the hood) for more info.
     *
     * Possible values: 'name', 'size', 'date'
     *
     * Default value: 'name'
     */
    'sort_method' => Helpers::env('SORT_METHOD', 'name'),

    /*
     * Whether or not to reverse the sort order.
     *
     * Default value: false
     */
    'reverse_sort' => Helpers::env('REVERSE_SORT', false),
];
