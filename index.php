<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>UberGallery</title>
    <link rel="shortcut icon" href="resources/images/favicon.png" />
    
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

<?php
    include_once('ubergallery/UberGallery.php');
    $gallery = new UberGallery();
    $gallery->createGallery('gallery-images');
?>

</body>
</html>