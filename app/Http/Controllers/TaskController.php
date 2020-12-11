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
      if(Auth::user()->id !== $folder->user_id){
        abort(403);
      }

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

    public function showEditForm(Folder $folder,int $task_id){
      $task = Task::find($task_id);

      return view('tasks/edit',[
        'task' => $task,
      ]);
    }

    public function edit(Folder $folder,int $task_id,EditTask $request){
      $task = Task::find($task_id);

      $task->title = $request->title;
      $task->status = $request->status;
      $task->due_date = $request->due_date;
      $task->save();

      return redirect()->route('tasks.index',[
        'folder' => $task->folder_id,
      ]);
    }
}
