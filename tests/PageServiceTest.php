<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\PageService;

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
        $page = $this->service->createPage((array) factory(App\Page::class)->make());
        $this->assertNotNull($page->id);
        $this->assertNotNull($this->service->findById($page->id));
    }
    
    public function testBasicDelete() {
        $page = $this->service->createPage((array) factory(App\Page::class)->make());
        $this->service->deletePage($page);
        $this->assertNull($this->service->findById($page->id));
    }
    
    public function testBasicUpdate() {
        $page = $this->service->createPage((array) factory(App\Page::class)->make());
        $page2 = $page->replicate();
        $this->service->updatePage($page, $page2);
    }
    
    public function testElementCountDecreaseOnUpdate() {
        $page = $this->service->createPage((array) factory(App\Page::class)->make());
        PageTestUtils::fillPage($page);
        $page->elements;
        $page2 = $page->replicate();
        $array = [];
        foreach($page2-> elements as $element){
            array_push($array,$element);
        }
        array_splice($array, 1,1);
        $page2->elements = $array;
        $this->service->updatePage($page, $page2);
        $this->assertEquals(3, $this->service->findById($page->id)->elements->count());
    }   
    
    public function testElementCountIncreaseOnUpdate() {
        $page = $this->service->createPage((array) factory(App\Page::class)->make());
        PageTestUtils::fillPage($page);
        $page->elements;
        $page2 = $page->replicate();
        $array = [];
        foreach($page2-> elements as $element){
            array_push($array,$element);
        }
        array_push($array, factory(App\Page::class)->make());
        $page2->elements = $array;
        $this->service->updatePage($page, $page2);
        $this->assertEquals(5, $this->service->findById($page->id)->elements->count());
    }
    
    public function testAllElementsRemovedOnUpdate() {
        $page = $this->service->createPage((array) factory(App\Page::class)->make());
        PageTestUtils::fillPage($page);
        $page2 = $page->replicate();
        $array = [];
        $page2->elements = $array;
        $this->service->updatePage($page, $page2);
        $this->assertEquals(0, $this->service->findById($page->id)->elements->count()); 
    }
    
}

class PageTestUtils {
        
    public static function fillPage($page){
        $page->elements()->saveMany(factory(App\TextElement::class,4)->make()); 
    }    
}
