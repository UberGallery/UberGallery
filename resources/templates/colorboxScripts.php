<script type="text/javascript" src="<?php echo $path; ?>"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $("a[rel='colorbox']").colorbox({maxWidth: "90%", maxHeight: "90%", opacity: ".5"});
    });
	/*
The code below will change click behavior
click on the left part of the image will go to the previous
click on the right part of the image will go to the next
credit - Jack Moore - http://www.jacklmoore.com/colorbox/
*/
	jQuery(document).on('cbox_complete', function(){
	jQuery('.cboxPhoto').off('click.cbox').on('click.cbox', function(e){
		if (e.offsetX < (e.target.width / 2)) {
			jQuery.colorbox.prev();
		} else {
			jQuery.colorbox.next();
		}
	});
});
</script>
