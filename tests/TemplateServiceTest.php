<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\TemplateService;
include_once '\tests\TestUtils.php';

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

        $template = $this->service->createTemplate(TestUtils::getTemplateArray());
        $this->assertNotNull($template->id);
        $this->assertNotNull($this->service->findById($template->id));
    }
    
    public function testSavesPages(){

        $template = TestUtils::getTemplateArray();
        for($i=0;$i<4;$i++){
            $template['pages'] []= TestUtils::getElementArray();
        }
        $template = $this->service->createTemplate($template);
        $this->assertEquals(4, $this->service->findById($template->id)->pages->count());
    }
   
    public function testBasicDelete() {

        $template = $this->service->createTemplate(TestUtils::getTemplateArray());
        $this->service->deleteTemplate($template->id);
        $this->assertNull($this->service->findById($template->id));
    }
    
    public function testBasicUpdate() {

        $template = $this->service->createTemplate(TestUtils::getTemplateArray());
        $template2 = TestUtils::getArrayFromTemplate($template);
        $template2['name'] = "testname";
        $this->service->updateTemplate($template, $template2);
        $this->assertEquals($this->service->findById($template->id)->name,"testname");
    }
    
    public function testPagesCountDecreaseOnUpdate() {

        $template = $this->service->createTemplate(TestUtils::getTemplateArray());
        TemplateTestUtils::fillTemplate($template);
        $template->pages;
        $template2 = TestUtils::getArrayFromTemplate($template);
        $array = [];
        foreach($template-> pages as $page){
            array_push($array,TestUtils::getArrayFromPage($page));
        }
        array_splice($array, 1,1);
        $template2['pages'] = $array;
        $this->service->updateTemplate($template, $template2);
        $this->assertEquals(3, $this->service->findById($template->id)->pages->count());
    }   
    
    public function testPagesCountIncreaseOnUpdate() {

        $template = $this->service->createTemplate(TestUtils::getTemplateArray());
        TemplateTestUtils::fillTemplate($template);
        $template->pages;
        $template2 = TestUtils::getArrayFromTemplate($template);
        $array = [];
        foreach($template-> pages as $page){
            array_push($array,TestUtils::getArrayFromPage($page));
        }
        array_push($array, TestUtils::getPageArray());
        $template2['pages'] = $array;
        $this->service->updateTemplate($template, $template2);
        $this->assertEquals(5, $this->service->findById($template->id)->pages->count());
    }
    
    public function testAllPagesRemovedOnUpdate() {
        
        $template = $this->service->createTemplate(TestUtils::getTemplateArray());
        TemplateTestUtils::fillTemplate($template);
        $template->pages;
        $template2 = TestUtils::getArrayFromTemplate($template);
        $template2['pages'] = [];
        $this->service->updateTemplate($template, $template2);
        $this->assertEquals(0, $this->service->findById($template->id)->pages->count()); 
    }

    public function testAssociatesUser() {
        $service = new TemplateService(TestUtils::getNonAdminUser());
        $template = $service->createTemplate(TestUtils::getTemplateArray());
        $this->assertEquals(22,$this->service->findById($template->id)->user_id);
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
