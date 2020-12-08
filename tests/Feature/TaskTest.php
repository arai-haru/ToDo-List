<?php

namespace Tests\Feature;

use App\Http\Requests\CreateTask;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //テストケースごとにデータベースをリフレッシュしてマイグレーションを再実行する
    use RefreshDatabase;

    // public function testExample()
    // {
    //     $response = $this->get('/');
    //
    //     $response->assertStatus(200);
    // }

    public function setUp() :void {
      parent::setUp();

      //テストケース実行前にフォルダデータを作成する
      $this->seed('FoldersTableSeeder');
    }

    public function test_due_date_should_be_date(){
      $response = $this->post('/folders/1/tasks/create',[
        'title' => 'Sample task',
        'due_date' => 123,
      ]);

      $response->assertSessionHasErrors([
        'due_date' => '期限日 には日付を入力してください。',
      ]);
    }

    public function test_due_date_should_not_be_past(){
      $response = $this->post('/folders/1/tasks/create',[
        'title' => 'Sample task',
        'due_date' => Carbon::yesterday()->format('Y/m/d'),
      ]);

      $response->assertSessionHasErrors([
        'due_date' => '期限日 には今日以降の日付を入力してください。',
      ]);
    }
}
