<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\AlbumService;

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
        $album = $this->service->createAlbum(factory(App\Album::class)->make());
        $this->assertNotNull($album->id);
        $this->assertNotNull($this->service->findById($album->id));
    }

    public function testBasicDelete(){
        $album = $this->service->createAlbum(factory(App\Album::class)->make());
        $id = $album->id;
        $this->service->deleteAlbum($album);
        $this->assertNull($this->service->findById($id));
    }

    public function testImagesDecrement(){
        $album = $this->service->createAlbum(factory(App\Album::class)->make());
        AlbumTestUtils::fillAlbum($album);
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
        $album = $this->service->createAlbum(factory(App\Album::class)->make());
        AlbumTestUtils::fillAlbum($album);
        $album->images;
        $image_id = $album->images[1]->id;
        $album2 = $album->replicate();
        $album2->images = array();
        $this->service->updateAlbum($album,$album2);
        $this->assertEquals(0,$this->service->findById($album->id)->images->count());    
    }

    public function testAfterRemovingTheyStillStay(){
        $album = $this->service->createAlbum(factory(App\Album::class)->make());
        AlbumTestUtils::fillAlbum($album);
        $album->images;
        $image_id = $album->images[1]->id;
        $album2 = $album->replicate();
        $album2->images = array();
        $this->service->updateAlbum($album,$album2);
        $this->assertNotNUll(App\Image::find($image_id));    
    }
}

class AlbumTestUtils {
        
    public static function fillAlbum($album){
        $album->images()->saveMany(factory(App\Image::class,4)->make()); 
    }    
}
