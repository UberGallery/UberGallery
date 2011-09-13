<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>UberGallery</title>
    <link rel="shortcut icon" href="<?php echo THEMEPATH; ?>/images/favicon.png" />
    
    <link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/rebase-min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/style.css" />
    <?php echo $gallery->getColorboxStyles(5); ?>
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script type="text/javascript" src="resources/colorbox/jquery.colorbox.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $("a[rel='colorbox']").colorbox({maxWidth: "90%", maxHeight: "90%", opacity: ".5"});
        });
    </script>
    
    <?php file_exists('googleAnalytics.inc') ? include('googleAnalytics.inc') : false; ?>
    
</head>
<body>

<!-- Start UberGallery v<?php echo UberGallery::VERSION; ?> - Copyright (c) <?php echo date('Y'); ?> Chris Kankiewicz (http://www.ChrisKankiewicz.com) -->
<div id="galleryWrapper">
    <h1>UberGallery</h1>
    <div class="line"></div>
    
    <?php if($gallery->getSystemMessages()): ?>
        <ul id="systemMessages">
            <?php foreach($gallery->getSystemMessages() as $message): ?>
                <li class="<?php echo $message['type']; ?>">
                    <?php echo $message['text']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <div id="galleryListWrapper">
        <ul id="galleryList" class="clearfix">
            <?php foreach ($galleryArray['images'] as $image): ?>
                <li><a href="<?php echo $image['file_path']; ?>" title="<?php echo $image['file_title']; ?>" rel="colorbox"><img src="<?php echo $image['thumb_path']; ?>" alt="<?php echo $image['file_title']; ?>"/></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div class="line"></div>
    <div id="galleryFooter" class="clearfix">
    
        <?php if ($galleryArray['stats']['total_pages'] > 1): ?>
        <ul id="galleryPagination">
            <li class="title">Page <?php echo $galleryArray['stats']['current_page']; ?> of <?php echo $galleryArray['stats']['total_pages']; ?></li>
            
            <?php if ($galleryArray['stats']['current_page'] > 1): ?>
                <li><a title="Previous Page" href="?page=<?php echo $galleryArray['stats']['current_page'] - 1; ?>">&lt;</a></li>
            <?php else: ?>
                <li class="inactive">&lt;</li>
            <?php endif; ?>
            
            <?php for($x = 1; $x <= $galleryArray['stats']['total_pages']; $x++): ?>
                <?php if($x == $galleryArray['stats']['current_page']): ?>
                    <li class="current"><?php echo $x; ?></li>
                <?php else: ?>
                    <li><a title="Page <?php echo $x; ?>" href="?page=<?php echo $x; ?>"><?php echo $x; ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($galleryArray['stats']['current_page'] < $galleryArray['stats']['total_pages']): ?>
                <li><a title="Next Page" href="?page=<?php echo $galleryArray['stats']['current_page'] + 1; ?>">&gt;</a></li>
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
