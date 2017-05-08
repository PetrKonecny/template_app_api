<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\TemplateInstanceService;
use App\Services\TemplateService;
use App\TemplateInstance;
include_once '\tests\TestUtils.php';

class TemplateInstanceServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseTransactions;
    
    public function setUp(){
        parent::setUp();
        $this->service = new TemplateInstanceService(TestUtils::getNonAdminUser());
    }
    
    
    public function testBasicSave(){

        $templateInst = TestUtils::getTemplateInstanceArray();
        $templateInst = $this->service->createTemplateInstance($templateInst);
        $this->assertNotNull($templateInst->id);
        $this->assertNotNull($this->service->findById($templateInst->id));
    }
    
    public function testSavesContents(){

        $templateInst = TestUtils::getTemplateInstanceArray();
        for($i=0;$i<4;$i++){
            $templateInst['contents'] []= TestUtils::getTextContentArray();
        }
        $templateInst = $this->service->createTemplateInstance($templateInst);
        $this->assertEquals(4, $this->service->findById($templateInst->id)->contents->count());
    }
   
    public function testBasicDelete() {

        $templateInst = TestUtils::getTemplateInstanceArray();
        //$templateInst['template_id'] = $this->template->id;
        $templateInst = $this->service->createTemplateInstance($templateInst);        
        $this->service->deleteTemplateInstance($templateInst->id);
        $this->assertNull(TemplateInstance::find($templateInst->id));
    }

    public function testAssociatesWithUser() {
        $templateInst = TestUtils::getTemplateInstanceArray();
        $templateInst = $this->service->createTemplateInstance($templateInst);
        $this->assertEquals(22, $templateInst->user_id);
    }

    public function validatesTemplateExists() {

    }
    
}
