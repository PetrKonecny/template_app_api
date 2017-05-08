<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\ContentService;
include_once '\tests\TestUtils.php';

class ContentServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseTransactions;
    
    public function setUp(){
        parent::setUp();
        $this->service = new ContentService();
    }

    public function testBasicSave(){
        $content = $this->service->createContent(TestUtils::getTextContentArray());
        $this->assertNotNull($content->id);
        $this->assertNotNull($this->service->findById($content->id));
    }
    
    public function testBasicDelete() {
        $content = $this->service->createContent(TestUtils::getTextContentArray());
        $this->service->deleteContent($content);
        $this->assertNull($this->service->findById($content->id));
    }

    public function testSavesImageInContent(){
        $image = new App\Image(TestUtils::getImageArray());
        $image->image_key = rand(0,11000000);
        $image->save();
        $content = TestUtils::getImageContentArray();
        $content['image'] = ['id' => $image->id];
        $content = $this->service->createContent($content); 
        $this->assertNotNull($content->image);
    }

    public function testUpdatesImageInContent(){
        $image = new App\Image(TestUtils::getImageArray());
        $image->image_key = rand(0,11000000);
        $image->save();
        $image2 = new App\Image(TestUtils::getImageArray());
        $image2->image_key = rand(0,11000000);
        $image2->save();
        $content = TestUtils::getImageContentArray();
        $content['image'] = ['id' => $image->id];
        $content = $this->service->createContent($content); 
        $contentArray = ['type'=>'image_content','id'=>$content->id,'image' => ['id'=>$image2->id]];
        $content = $this->service->updateContent($content,$contentArray);
        $this->assertEquals($image2->id,$this->service->findById($content->id)->image->id);
    }

    public function testRemovesImageIfNonePresent(){
        $image = new App\Image(TestUtils::getImageArray());
        $image->image_key = rand(0,11000000);
        $image->save();
        $content = TestUtils::getImageContentArray();
        $content['image'] = ['id' => $image->id];
        $content = $this->service->createContent($content);
        $contentArray = ['type'=>'image_content','id'=>$content->id]; 
        $content = $this->service->updateContent($content,$contentArray);
        $this->assertNull($this->service->findById($content->id)->image);
    }

    /**
    * @expectedException RuntimeException
    */  
    public function testValidatesImageInDB(){
        $content = TestUtils::getImageContentArray();
        $content['image'] = ['id' => "122454214"];
        $content = $this->service->createContent($content);
    }

    /**
    * @expectedException RuntimeException
    */ 
    public function testValidatesImageAccess(){
        $image = new App\Image(TestUtils::getImageArray());
        $image->image_key = rand(0,11000000);
        $image ->user_id = 23;
        $image->save();
        $content = TestUtils::getImageContentArray();
        $content['image'] = ['id' => $image->id];
        $service = new ContentService(TestUtils::getNonAdminUser());
        $content = $service->createContent($content);
    }

    /**
    * @expectedException RuntimeException
    */ 
    public function testValidatesParamsAreNumbers(){
        $content = TestUtils::getImageContentArray();
        $content['width'] = "text";
        $content = $this->service->createContent($content);
    }

    public function testSavesImageContent(){
        $content = $this->service->createContent(TestUtils::getImageContentArray());
        $this->assertEquals('image_content',$this->service->findById($content->id)->type);
    }

    public function testSavesTextContent(){
        $content = $this->service->createContent(TestUtils::getTextContentArray());
        $this->assertEquals('text_content',$this->service->findById($content->id)->type);
    }

    public function testSavesTableContent(){
        $content = $this->service->createContent(TestUtils::getTableContentArray());
        $this->assertEquals('table_content',$this->service->findById($content->id)->type);
    }

}
/*
class TestUtils {
   
    public static function getTextContentArray(){
        return [
            'type' => 'text_content',
            'text' => str_random(10)
        ];
    }

    public static function getImageContentArray(){
        return [
            'type' => 'image_content',
            'image' => null
        ];
    }

    public static function getTableContentArray(){
        $rows = [['text'=>'cell_1'],['text'=>'cell_2']];
        return [
            'type' => 'table_content',
            'rows' => json_encode($rows)
        ];
    }

    public static function getImageArray(){
        return [
        'name' => "picture",
        ];
    } 

}
*/