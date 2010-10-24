<?php include_once('UberGallery.php'); $gallery = new UberGallery(); ?>

<!-- Start UberGallery <?php echo UberGallery::VERSION; ?> - Copyright (c) <?php echo date('Y'); ?> Chris Kankiewicz (http://www.ChrisKankiewicz.com) -->
<div id="galleryWrapper">
    <ul id="galleryList" class="clearfix">
        <?php foreach ($gallery->readImageDirectory('gallery-images') as $image) :?>
            <li><a href="<?php echo $image['file_path']; ?>" title="<?php echo $image['file_title']; ?>" id="img-0" rel="colorbox"><img src="<?php echo $image['thumb_path']; ?>" alt="<?php echo $image['file_title']; ?>"/></a></li>
        <?php endforeach; ?>
    </ul>
    <div id="galleryFooter" class="clearfix">
        <div id="credit">Powered by, <a href="http://www.ubergallery.net">UberGallery</a></div>
    </div>
</div>
<!-- End UberGallery - Licensed under the MIT License <http://creativecommons.org/licenses/MIT/> -->
