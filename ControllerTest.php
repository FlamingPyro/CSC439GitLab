<?php
require_once 'Main.php';

use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    private $model;
    private $view;
    private $sut;
    
    public function setUp() :void {
        $d = new YahtzeeDice();
        $this->model = new Yahtzee($d);
        $this->view = $this->createStub(YahtzeeView::class);
        $this->sut = new YahtzeeController($this->model, $this->view);
    } 
    /**
    * @covers \YahtzeeController::get_model
    */
    public function test_get_model(){
        $result = $this->sut->get_model();
        $this->assertNotNull($result);
    }
    /**
    * @covers::get_view
    */
    public function test_get_view(){
        $result = $this->sut->get_view();
        $this->assertNotNull($result);
    }
    
    public function get_possible_categories(){
        $model = $this->createStub(Yahtzee::class);
        $model->method('get_scorecard')->willReturn([
            'Ones' => null,
            'Twos' => 4,
            'Threes' => null,
        ]);
        $sut = new YahtzeeController($model, $this->view);

        $result = $sut->get_possible_categories();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('Ones', $result);
        $this->assertArrayHasKey('Threes', $result);
        $this->assertArrayNotHasKey('Twos', $result);
    }
    
    public function process_score_input(){
        $result = $this->sut->process_score_input("exit");
        $this->assertEquals(-1, $result);

        $model = $this->createStub(Yahtzee::class);
        $model->method('get_scorecard')->willReturn(['Ones' => null]);
        $model->method('get_kept_dice')->willReturn([1, 2, 3, 4, 5]);
        $sut = new YahtzeeController($model, $this->view);

        $result = $sut->process_score_input("InvalidCategory");
        $this->assertEquals(-2, $result);

        $model = $this->createStub(Yahtzee::class);
        $model->method('get_scorecard')->willReturn(['Ones' => null]);
        $model->method('get_kept_dice')->willReturn([1, 1, 1, 2, 2]);
        $sut = new YahtzeeController($model, $this->view);

        $result = $sut->process_score_input("Ones");
        $this->assertEquals(0, $result);
    }
}

?>