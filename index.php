<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>UberGallery</title>
    <link rel="shortcut icon" href="resources/images/favicon.png" />
    
    <link rel="stylesheet" type="text/css" href="resources/css/rebase.css" />
    <link rel="stylesheet" type="text/css" href="resources/css/style.css" />
    <link rel="stylesheet" type="text/css" href="resources/css/colorbox.css" />
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
    <script type="text/javascript" src="resources/js/jquery.colorbox.js"></script>
    
    <script type="text/javascript">
    $(document).ready(function(){
        $("a[rel='colorbox']").colorbox({maxWidth: "90%", maxHeight: "90%", opacity: ".5"});
    });
    </script>  
</head>
<body>

<?php include_once('resources/UberGallery.php'); $gallery = UberGallery::init()->readImageDirectory('gallery-images'); ?>

<!-- Start UberGallery v<?php echo UberGallery::VERSION; ?> - Copyright (c) ' . date('Y') . ' Chris Kankiewicz (http://www.ChrisKankiewicz.com) -->
<div id="galleryWrapper">
    <h1>Uber Gallery</h1>
    <div class="line"></div>
    
    <div id="galleryListWrapper">
        <ul id="galleryList" class="clearfix">
            <?php foreach ($gallery['images'] as $image): ?>
                <li><a href="<?php echo $image['file_path']; ?>" title="<?php echo $image['file_title']; ?>" rel="colorbox"><img src="<?php echo $image['thumb_path']; ?>" alt="<?php echo $image['file_title']; ?>"/></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div class="line"></div>
    <div id="galleryFooter" class="clearfix">
    
        <?php if ($gallery['stats']['total_pages'] > 1): ?>
        <ul id="galleryPagination">
            <li class="title">Page <?php echo $gallery['stats']['current_page']; ?> of <?php echo $gallery['stats']['total_pages']; ?></li>
            
            <?php if ($gallery['stats']['current_page'] > 1): ?>
                <li><a title="Previous Page" href="index.php?page=<?php echo $gallery['stats']['current_page'] - 1; ?>">&lt;</a></li>
            <?php else: ?>
                <li class="inactive">&lt;</li>
            <?php endif; ?>
            
            <?php for($x = 1; $x <= $gallery['stats']['total_pages']; $x++): ?>
                <?php if($x == $gallery['stats']['current_page']): ?>
                    <li class="current"><?php echo $x; ?></li>
                <?php else: ?>
                    <li><a title="Page <?php echo $x; ?>" href="index.php?page=<?php echo $x; ?>"><?php echo $x; ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($gallery['stats']['current_page'] < $gallery['stats']['total_pages']): ?>
                <li><a title="Next Page" href="index.php?page=<?php echo $gallery['stats']['current_page'] + 1; ?>">&gt;</a></li>
            <?php else: ?>
                <li class="inactive">&gt;</li>
            <?php endif; ?>
        </ul>
        <?php endif; ?>
        
        <div id="credit">Powered by, <a href="http://www.ubergallery.net">UberGallery</a></div>
    </div>
</div>
<!-- End UberGallery - Dual licensed under the MIT & GPL license -->

</body>
</html>