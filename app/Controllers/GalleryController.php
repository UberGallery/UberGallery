<?php

namespace App\Controllers;

class GalleryController extends Controller
{
    public function index()
    {
        // TODO: List available albums?
        return $this->view('redux.index');
    }
}
