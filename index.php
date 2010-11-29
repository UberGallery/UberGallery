<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>UberGallery</title>
    <link rel="shortcut icon" href="resources/images/favicon.png" />
    
    <link rel="stylesheet" type="text/css" href="resources/css/rebase.css" />
    <link rel="stylesheet" type="text/css" href="resources/css/style.css" />
    <link rel="stylesheet" type="text/css" href="resources/css/ubergallery.css" />
    <link rel="stylesheet" type="text/css" href="resources/css/colorbox.css" />
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
    <script type="text/javascript" src="resources/js/jquery.colorbox.js"></script>
    
    <script type="text/javascript">
    $(document).ready(function(){
        $("a[rel='colorbox']").colorbox({maxWidth: "90%", maxHeight: "90%", opacity: ".5"});
    });
    </script>  
</head>
<body>

<?php // include_once('resources/UberGallery.php'); $gallery = UberGallery::factory()->createGallery('gallery-images'); ?>

<?php include_once('resources/UberGallery.php'); $gallery = new UberGallery(); ?>

<!-- Start UberGallery ' . UberGallery::VERSION .' - Copyright (c) ' . date('Y') . ' Chris Kankiewicz (http://www.ChrisKankiewicz.com) -->
<div id="galleryWrapper">
    <div id="galleryHeader" class="clearfix">
        <h1>Uber Gallery</h1>
    </div>
    
    <div id="galleryListWrapper">
        <ul id="galleryList" class="clearfix">
            <?php foreach ($gallery->readImageDirectory('gallery-images') as $image): ?>
                <li><a href="<?php echo $image['file_path']; ?>" title="<?php echo $image['file_title']; ?>" rel="colorbox"><img src="<?php echo $image['thumb_path']; ?>" alt="<?php echo $image['file_title']; ?>"/></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div id="galleryFooter" class="clearfix">
        <ul id="galleryPagination">
            <li class="title">Page 1 of 3</li>
            <li class="inactive">&lt;</li>
            <li class="current">1</li>
            <li><a title="Page 2" href="index.php?page=2">2</a></li>
            <li><a title="Page 3" href="index.php?page=3">3</a></li>
            <li><a title="Next Page" href="index.php?page=2">&gt;</a></li>
          </ul>
        <div id="credit">Powered by, <a href="http://www.ubergallery.net">UberGallery</a></div>
    </div>
</div>
<!-- End UberGallery - Dual licensed under the MIT & GPL license -->

</body>
</html>