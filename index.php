<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>UberGallery</title>
    <link rel="shortcut icon" href="ubergallery/resources/images/images.png" />
    
    <link rel="stylesheet" type="text/css" href="ubergallery/resources/css/ubergallery.css" />
    <link rel="stylesheet" type="text/css" href="ubergallery/resources/css/colorbox.css" />
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="ubergallery/resources/js/jquery.colorbox.js"></script>
    
    <script type="text/javascript">
    $(document).ready(function(){
        $("a[rel='colorbox']").colorbox({maxWidth: "90%", maxHeight: "90%", opacity: ".5"});
    });
    </script>  
</head>
<body>

<?php include_once('UberGallery.php'); $gallery = new UberGallery(); ?>

<!-- Start UberGallery <?php echo UberGallery::VERSION; ?> - Copyright (c) <?php echo date('Y'); ?> Chris Kankiewicz (http://www.ChrisKankiewicz.com) -->
<div id="gallery-wrapper">
    <div id="gallery-constraint">
        <ul id="gallery-images" class="clearfix">
            <?php foreach ($gallery->readImageDirectory('gallery-images') as $image) :?>
                <li><a href="<?php echo $image['file_path']; ?>" title="<?php echo $image['file_title']; ?>" id="img-0" rel="colorbox"><img src="<?php echo $image['thumb_path']; ?>" alt="<?php echo $image['file_title']; ?>"/></a></li>
            <?php endforeach; ?>
        </ul>
        <div id="uber-footer" class="clearfix">
            <div id="credit">Powered by, <a href="http://www.ubergallery.net">UberGallery</a></div>
        </div>
    </div>
</div>
<!-- End UberGallery - Licensed under the MIT License <http://creativecommons.org/licenses/MIT/> -->

</body>
</html>