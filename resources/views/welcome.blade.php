@extends('layouts.app')

@section('content')
  Welcome to the ImageResizer demo page.<br/>
  To test out the image resizing service, please upload an image file.<br/>

  <form method="post" action="/image" enctype="multipart/form-data" name="demoForm" style="margin-top: 10px;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="form-group">
      <input type="file" name="demoFile" class="btn btn-default" required="true" onchange="document.demoForm.submit()" />
    </div>
  </form>
@endsection
