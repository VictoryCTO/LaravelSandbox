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
  
  this.imageClicked = function(resource_id) {
    if (confirm('Do you want to delete this image?')) {
      window.location = '/delete/'+resource_id;
    }
  }
  
  this.initialize();
}
