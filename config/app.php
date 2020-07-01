<?php

use App\Support\Helpers;

return [
    /*
     * Enable application debugging and display error messages.
     *
     * !!! WARNING !!!
     * It is recommended that debug remains OFF unless troubleshooting an issue.
     * Leaving this enabled WILL cause leakage of sensitive server information.
     *
     * Default value: false
     */
    'debug' => Helpers::env('APP_DEBUG', false),

    /*
     * The application interface language.
     *
     * Possible values: See 'app/translations' folder for available translations.
     *
     * Defualt value: 'en'
     */
    'language' => Helpers::env('APP_LANGUAGE', 'en'),

    /*
     * Give your gallery a descriptive title.
     *
     * Default value: 'Uber Gallery'
     */
    'gallery_title' => 'Uber Gallery',

    /*
     * Default date format. For additional info on date formatting see:
     * https://www.php.net/manual/en/function.date.php.
     *
     * Default value: 'Y-m-d H:i:s'
     */
    'date_format' => Helpers::env('DATE_FORMAT', 'Y-m-d H:i:s'),

    /*
     * Timezone used for date formatting. For a list of supported timezones see:
     * https://www.php.net/manual/en/timezones.php.
     *
     * Default value: The server's timezone
     */
    'timezone' => Helpers::env('TIMEZONE', date_default_timezone_get()),
];
