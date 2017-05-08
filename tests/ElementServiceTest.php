<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\ElementService;
include_once '\tests\TestUtils.php';

class ElementServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseTransactions;
    
    public function setUp(){
        parent::setUp();
        $this->service = new ElementService();
    }

    public function testBasicSave(){
        $element = $this->service->createElement(TestUtils::getElementArray());
        $this->assertNotNull($element->id);
        $this->assertNotNull($this->service->findById($element->id));
    }
    
    public function testBasicDelete() {
        $element = $this->service->createElement(TestUtils::getElementArray());
        $this->service->deleteElement($element);
        $this->assertNull($this->service->findById($element->id));
    }

    public function testSavesImageElement() {
        $element = TestUtils::getElementArray();
        $element['type'] = 'image_element';
        $element = $this->service->createElement($element);
        $this->assertEquals('image_element',$this->service->findById($element->id)->type);
    }

    public function testSavesTextElement() {
        $element = TestUtils::getElementArray();
        $element['type'] = 'text_element';
        $element = $this->service->createElement($element);
        $this->assertEquals('text_element',$this->service->findById($element->id)->type);
    }

    public function testSavesFrameElement() {
        $element = TestUtils::getElementArray();
        $element['type'] = 'frame_element';
        $element = $this->service->createElement($element);
        $this->assertEquals('frame_element',$this->service->findById($element->id)->type);
    }

    public function testSavesTableElement() {
        $element = TestUtils::getElementArray();
        $element['type'] = 'table_element';
        $element = $this->service->createElement($element);
        $this->assertEquals('table_element',$this->service->findById($element->id)->type);
    }

    public function testSavesItsContent(){
        $element = TestUtils::getElementArray();
        $element['content'] = TestUtils::getTextContentArray();
        $element = $this->service->createElement($element);
        $this->assertNotNull($element->content->id);
    }

    public function updatesPosition(){
        $element = $this->service->createElement(TestUtils::getElementArray());
        $element = $this->service->createElement($element);
        $elementArray = TestUtils::getArrayFromElement($element);
        $element->positionX = 111;
        $element->positionY = 111;
        $element = $this->service->updateElement($element,TestUtils::getArrayFromElement($element));
        $this->assertEquals(111,$element->positionX);
        $this->assertEquals(111,$element->positionY);
    }

    public function doesntRemoveContentIfNoneGiven(){
        $element = TestUtils::getElementArray();
        $element['content'] = TestUtils::getTextContentArray();
        $element = $this->service->createElement($element);
        $elementArray = TestUtils::getArrayFromElement($element);
        $elementArray['content'] = "";
        $element = $this->service->updateElement($element,$elementArray); 
        $this->assertNotNull($element->content->id);      
    }

    public function testCantInjectAnotherUsersContent(){
        $element = TestUtils::getElementArray();
        $element['content'] = TestUtils::getTextContentArray();
        $service = new ElementService(TestUtils::getNonAdminUser());
        $element = $service->createElement($element);
        $service = new ElementService(TestUtils::getNonAdminUser(28));
        $element2 = TestUtils::getElementArray();
        $element2 = $this->service->createElement($element2);
        $injectedContentId = $element->content->id;
        $elementArray = TestUtils::getElementArray();
        $elementArray['content'] = TestUtils::getTextContentArray();
        $elementArray['content']['id'] = $injectedContentId;
        $service->updateElement($element2,$elementArray);
        $this->assertNull($element2->content);
    }

    /**
    * @expectedException RuntimeException
    */
    public function testValidatesImageAccessForUser(){
        $image = new App\Image(['name' => "picture"]);
        $image->image_key = rand(0,11000000);
        $image ->user_id = 23;
        $image->save();
        $element = TestUtils::getElementArray();
        $element['type'] = 'image_element';
        $element['image'] = ['id' => $image->id];
        $service = new ElementService(TestUtils::getNonAdminUser());
        $content = $service->createElement($element);
    }


    /**
    * @expectedException RuntimeException
    */    
    public function testValidatesType(){
        $element = TestUtils::getElementArray();
        $element['type'] = "other_element";
        $element = $this->service->createElement($element);
    }

    /**
    * @expectedException RuntimeException
    */    
    public function testValidatesWidth(){
        $element = TestUtils::getElementArray();
        $element['width'] = "test";
        $element = $this->service->createElement($element);
    }

    /**
    * @expectedException RuntimeException
    */  
    public function testValidatesHeight(){
        $service = new ElementService(TestUtils::getNonAdminUser());
        $element = TestUtils::getElementArray();
        $element['height'] = "test";
        $element = $this->service->createElement($element);
    }

    /**
    * @expectedException RuntimeException
    */  
    public function testValidatesX(){
        $element = TestUtils::getElementArray();
        $element['positionX'] = "test";
        $element = $this->service->createElement($element);
    }

    /**
    * @expectedException RuntimeException
    */  
    public function testValidatesY(){
        $element = TestUtils::getElementArray();
        $element['positionY'] = "test";
        $element = $this->service->createElement($element);
    }

}
/*
class TestUtils {
        
    public static function fillElement($element){
        $element->content()->save(factory(App\Content::class)->make()); 
    }

    public static function getElementArray(){
        return [
            'type' => 'text_element',
            'width' => rand(50, 300),
            'height' => rand(50, 300),
            'positionX' => rand(50, 300),
            'positionY' => rand(50, 300),
        ];
    }

    public static function getArrayFromElement($element){
        return [
            'type' => $element['type'],
            'width' => $element['width'],
            'height' => $element['height'],
            'positionX' => $element['positionX'],
            'positionY' => $element['positionY'],
        ];
    }

    public static function getTextContentArray(){
        return [
            'type' => 'text_content',
            'text' => str_random(10)
        ];
    }    
}*/
