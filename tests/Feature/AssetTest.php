<?php

namespace Tests\Feature;

use Tests\TestCase;

class AssetTest extends TestCase
{
    public function test_it_can_retrieve_an_asset()
    {
        $response = $this->get('/asset?path=css/styles.css');

        $this->assertTrue($response->isOk());
    }

    // public function test_it_can_not_access_files_outside_of_the_assets_directory()
    // {
    //     $response = $this->get('/asset?path=../index.twig');
    //
    //     $this->assertFalse($response->isOk());
    // }
}
