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
