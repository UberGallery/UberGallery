<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>UberGallery</title>
    <link rel="shortcut icon" href="<?php echo THEMEPATH; ?>/images/favicon.png" />
    
    <link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/rebase-min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo THEMEPATH; ?>/style.css" />
    <?php echo $gallery->getColorboxStyles(1); ?>
    
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script type="text/javascript" src="resources/colorbox/jquery.colorbox.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $("a[rel='colorbox']").colorbox({maxWidth: "90%", maxHeight: "90%", opacity: ".5"});
        });
    </script>
    
    <?php file_exists('googleAnalytics.inc') ? include('googleAnalytics.inc') : false; ?>
</head>

<body>
    
    <h1>UberGallery</h1>

    <?php $gallery->createGallery('gallery-images'); ?>

</body>

</html>
