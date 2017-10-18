<?php

// This is where we define our application routes

$route->get('/', 'GalleryController@index');
$route->get('/{album}', 'AlbumController@show');
$route->get('/{album}/{image}', 'ImageController@show');
$route->get('/{album}/thumbnail/{image}', 'ThumbnailController@show');
