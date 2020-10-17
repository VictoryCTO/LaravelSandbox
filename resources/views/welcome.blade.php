@extends('layouts.app')

@section('content')
  Welcome to the ImageResizer demo page.<br/>
  To test out the image resizing service, please upload an image file.<br/>

  <form method="post" action="/image" enctype="multipart/form-data" name="demoForm" id="demoForm" style="margin-top: 10px;">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="form-group">
      <input type="file" name="demoFile" id="demoFile" class="btn btn-default" required="true" autocomplete="off" />
    </div>
  </form>
  <script type="text/javascript">
  function UploadPageManager(formElId, fileElId) {
    this.formEl = document.getElementById(formElId);
    this.fileEl = document.getElementById(fileElId);
    
    this.initialize = function() {
      this.fileEl.addEventListener(
         'change',
         function() {this.submitForm()}.bind(this)
      );
    }
    
    this.submitForm = function() {
      this.formEl.submit();
    };
    
    this.initialize();
  }
  var thisPageManager;
  
  window.onload = function() {
    thisPageManager = new UploadPageManager("demoForm", "demoFile");
  }
  </script>
@endsection
