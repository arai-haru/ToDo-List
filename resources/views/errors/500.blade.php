@extends('layout')

@section('content')
<div class="contaner">
  <div class="row">
    <div class="col col-md-offset-3 col-md-6">
      <div class="text-center">
        <p>システムエラーで表示ができませんでした。</p>
        <a href="{{ route('home')}}" class="btn">ホームへ戻る</a>
      </div>
    </div>
  </div>
</div>
@endsection
