<?php foreach ($gallery->albums() as $album): ?>
    <div class="gallery">
        <?php foreach ($album->images() as $image): ?>
            <a href="#" class="image">
                <img src="<?= $image->stream(); ?>">
            </a>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<style type="text/css" media="screen">
    body {
        background-color: #78909C;
    }

    a.image {
        box-shadow: 0 2px 4px rgba(0, 0, 0, .33);
        border-radius: 8px;
        display: inline-block;
        height: 240px;
        margin: 12px;
        overflow: hidden;
        position: relative;
        width: 240px;
        text-align: center;
    }

    a.image:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, .5);
        top: -2px;
    }

    .image img {
        min-height: 100%;
        object-fit: cover;
        width: 100%;
    }
</style>
