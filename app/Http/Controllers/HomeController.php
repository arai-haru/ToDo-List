<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
      //ログインユーザを取得
      $user = Auth::user();

      //ログインユーザに紐づくフォルダを一つ取得
      $folder = $user->folders()->first();

      if(is_null($folder)){
        return view('home');
      }

      return redirect()->route('tasks.index',[
        'id' =>$folder->id,
      ]);
    }
}
