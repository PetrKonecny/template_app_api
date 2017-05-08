<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\PageService;
include_once '\tests\TestUtils.php';

class PageServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseTransactions;
    
    public function setUp(){
        parent::setUp();
        $this->service = new PageService();
    }
    
    public function testBasicSave(){
        $page = $this->service->createPage(TestUtils::getPageArray());
        $this->assertNotNull($page->id);
        $this->assertNotNull($this->service->findById($page->id));
    }
    
    public function testBasicDelete() {
        $page = $this->service->createPage(TestUtils::getPageArray());
        $this->service->deletePage($page);
        $this->assertNull($this->service->findById($page->id));
    }
    
    public function testBasicUpdate() {
        $page = $this->service->createPage(TestUtils::getPageArray());
        $page2 = TestUtils::getArrayFromPage($page);
        $page2['width'] = 500;
        $page2['height'] = 500;
        $this->service->updatePage($page, $page2);
        $this->assertEquals(500,$this->service->findById($page->id)->width);
    }
    
    public function testElementCountDecreaseOnUpdate() {
        $page = $this->service->createPage(TestUtils::getPageArray());
        PageTestUtils::fillPage($page);
        $page->elements;
        $page2 = TestUtils::getArrayFromPage($page);
        $array = [];
        foreach($page->elements as $element){
            array_push($array,TestUtils::getArrayFromElement($element));
        }
        array_splice($array, 1,1);
        $page2['elements'] = $array;
        $this->service->updatePage($page, $page2);
        $this->assertEquals(3, $this->service->findById($page->id)->elements->count());
    }   
    
    public function testElementCountIncreaseOnUpdate() {
        $page = $this->service->createPage(TestUtils::getPageArray());
        PageTestUtils::fillPage($page);
        $page->elements;
        $page2 = TestUtils::getArrayFromPage($page);
        $array = [];
        foreach($page->elements as $element){
            array_push($array,TestUtils::getArrayFromElement($element));
        }
        array_push($array, TestUtils::getElementArray());
        $page2['elements'] = $array;
        $this->service->updatePage($page, $page2);
        $this->assertEquals(5, $this->service->findById($page->id)->elements->count());
    }
    
    public function testAllElementsRemovedOnUpdate() {
        $page = $this->service->createPage(TestUtils::getPageArray());
        PageTestUtils::fillPage($page);
        $page2 = TestUtils::getArrayFromPage($page);
        $page2['elements'] = [];
        $this->service->updatePage($page, $page2);
        $this->assertEquals(0, $this->service->findById($page->id)->elements->count()); 
    }
    
}

class PageTestUtils {
        
    public static function fillPage($page){
        $page->elements()->saveMany(factory(App\TextElement::class,4)->make()); 
    }    
}
