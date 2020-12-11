<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Folder $folder){
      //全てのフォルダを取得
      // $folders = Folder::all();

      //ユーザのフォルダを取得する
      $folders = Auth::user()->folders()->get();

      //選ばれたフォルダを取得
      // $current_folder = Folder::find($id);

      //選ばれたフォルダに紐づくタスクを取得
      $tasks = $folder->tasks()->get();

      return view('tasks/index',[
        'folders' => $folders,
        'current_folder_id' => $folder->id,
        'tasks' => $tasks,
      ]);
    }

    public function showCreateForm(Folder $folder){
      return view('tasks/create',[
        'folder_id' => $folder->id
      ]);
    }

    public function create(Folder $folder, CreateTask $request){
      // $current_folder = Folder::find($id);

      $task = new Task();
      $task->title = $request->title;
      $task->due_date = $request->due_date;

      $folder->tasks()->save($task);

      return redirect()->route('tasks.index',[
        'folder' => $folder->id,
      ]);
    }

    public function showEditForm(Folder $folder,Task $task){
      $this->checkRelation($folder,$task);

      // $task = Task::find($task_id);

      return view('tasks/edit',[
        'task' => $task,
      ]);
    }

    public function edit(Folder $folder,Task $task,EditTask $request){
      $this->checkRelation($folder,$task);

      // $task = Task::find($task_id);

      $task->title = $request->title;
      $task->status = $request->status;
      $task->due_date = $request->due_date;
      $task->save();

      return redirect()->route('tasks.index',[
        'folder' => $task->folder_id,
      ]);
    }

    public function checkRelation(Folder $folder,Task $task){
      if($folder->id !== $task->folder_id){
        abort(404);
      }
    }
}
