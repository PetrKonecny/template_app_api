<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\AlbumService;
include_once '\tests\TestUtils.php';

class AlbumServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseTransactions;
    
    public function setUp(){
        parent::setUp();
        $this->service = new AlbumService();
    }
    
    public function testBasicSave(){
        $album = $this->service->createAlbum(TestUtils::getAlbumArray());
        $this->assertNotNull($album->id);
        $this->assertNotNull($this->service->findById($album->id));
    }

    public function testBasicDelete(){
        $album = $this->service->createAlbum(TestUtils::getAlbumArray());
        $id = $album->id;
        $this->service->deleteAlbum($album);
        $this->assertNull($this->service->findById($id));
    }

    public function testImagesDecrement(){
        $album = $this->service->createAlbum(TestUtils::getAlbumArray());
        TestUtils::fillAlbum($album);
        $album->images;
        $album2 = $album->replicate();
        foreach ($album->images as $image) {
            $array []= $image;
        }
        array_splice($array, 1,1);
        $album2->images = $array;
        $this->service->updateAlbum($album,$album2);
        $this->assertEquals(3,$this->service->findById($album->id)->images->count());    
    }

    public function testUpdateRemovesAll(){
        $album = $this->service->createAlbum(TestUtils::getAlbumArray());
        TestUtils::fillAlbum($album);
        $album->images;
        $image_id = $album->images[1]->id;
        $album2 = $album->replicate();
        $album2->images = array();
        $this->service->updateAlbum($album,$album2);
        $this->assertEquals(0,$this->service->findById($album->id)->images->count());    
    }

    public function testAfterRemovingTheyStillStay(){
        $album = $this->service->createAlbum(TestUtils::getAlbumArray());
        TestUtils::fillAlbum($album);
        $album->images;
        $image_id = $album->images[1]->id;
        $album2 = $album->replicate();
        $album2->images = array();
        $this->service->updateAlbum($album,$album2);
        $this->assertNotNUll(App\Image::find($image_id));    
    }

    public function testBasicUpdate(){
        $album = $this->service->createAlbum(TestUtils::getAlbumArray());
        $album2 = TestUtils::getArrayFromAlbum($album);
        $album2['name']= "test";
        $this->service->updateAlbum($album,$album2);
        $this->assertEquals("test",$this->service->findById($album->id)->name);
    }

    
}

