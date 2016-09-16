<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\TemplateService;
use Tests\TestUtils;
class TemplateServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseTransactions;
    
    public function setUp(){
        parent::setUp();
        $this->service = new TemplateService();
    }
    
    
    public function testBasicSave(){

        $template = $this->service->createTemplate((array) factory(App\Template::class)->make());
        $this->assertNotNull($template->id);
        $this->assertNotNull($this->service->findById($template->id));
    }
    
    public function testNestedSave(){

        $template = factory(App\Template::class)->make();
        TemplateTestUtils::fillTemplateNoSave($template);
        $array = json_decode(json_encode($template), true);
        $template = $this->service->createTemplate($array);
        $this->assertEquals(4, $this->service->findById($template->id)->pages->count());
    }
   
    public function testBasicDelete() {

        $template = $this->service->createTemplate((array) factory(App\Template::class)->make());
        $this->service->deleteTemplate($template);
        $this->assertNull($this->service->findById($template->id));
    }
    
    public function testBasicUpdate() {

        $template = $this->service->createTemplate((array) factory(App\Template::class)->make());
        $template2 = $template->replicate();
        $template2->name = "testname";
        $this->service->updateTemplate($template, $template2);
        $this->assertEquals($this->service->findById($template->id)->name,"testname");
    }
    
    public function testPagesCountDecreaseOnUpdate() {

        $template = $this->service->createTemplate((array) factory(App\Template::class)->make());
        TemplateTestUtils::fillTemplate($template);
        $template->pages;
        $template2 = $template->replicate();
        $array = [];
        foreach($template2-> pages as $page){
            array_push($array,$page);
        }
        array_splice($array, 1,1);
        $template2->pages = $array;
        $this->service->updateTemplate($template, $template2);
        $this->assertEquals(3, $this->service->findById($template->id)->pages->count());
    }   
    
    public function testPagesCountIncreaseOnUpdate() {

        $template = $this->service->createTemplate((array) factory(App\Template::class)->make());
        TemplateTestUtils::fillTemplate($template);
        $template->pages;
        $template2 = $template->replicate();
        $array = [];
        foreach($template2-> pages as $page){
            array_push($array,$page);
        }
        array_push($array, factory(App\Page::class)->make());
        $template2->pages = $array;
        $this->service->updateTemplate($template, $template2);
        $this->assertEquals(5, $this->service->findById($template->id)->pages->count());
    }
    
    public function testAllPagesRemovedOnUpdate() {
        
        $template = $this->service->createTemplate((array) factory(App\Template::class)->make());
        TemplateTestUtils::fillTemplate($template);
        $template->pages;
        $template2 = $template->replicate();
        $array = [];
        $template2->pages = $array;
        $this->service->updateTemplate($template, $template2);
        $this->assertEquals(0, $this->service->findById($template->id)->pages->count()); 
    }
}

class TemplateTestUtils {
        
    public static function fillTemplate($template){
            $template->pages()->saveMany(factory(App\Page::class,4)->make());                 
    }
    
    public static function fillTemplateNoSave($template){
            $template->pages = factory(App\Page::class,4)->make();              
    }
    
    
}
