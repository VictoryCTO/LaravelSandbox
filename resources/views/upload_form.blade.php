<form method="post" action="/image" enctype="multipart/form-data" name="demoForm" id="demoForm" style="margin-top: 10px;">
  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
  <div class="form-group">
    <input type="file" name="demoFile" id="demoFile" class="btn btn-default" required="true" autocomplete="off" />
  </div>
</form>

<script type="text/javascript">
var thisUploadPageManager;

window.onload = function() {
  thisUploadPageManager = new UploadPageManager("demoForm", "demoFile");
}
</script>

<br/>