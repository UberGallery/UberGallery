<?php

    require('vendor/autoload.php');

    $config = new App\Config('config/gallery.php');

    if ($config->get('cache') != null) {
        $cache = Stash\Cache::make(
            $config->get('cache.driver'),
            $config->get('cache.config')
        );
    }

    $gallery = App\Gallery::create(__DIR__ . '/images', $config, $cache);

?>

<?php foreach ($gallery->albums() as $album): ?>
    <?php foreach ($album->images() as $image): ?>
        <img src="<?= $image->stream(); ?>">
    <?php endforeach; ?>
<?php endforeach; ?>
