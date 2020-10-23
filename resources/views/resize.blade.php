<html>
 <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
 </head>
 <body>
  <div class="container">
   <br />
   <br />   
   <form method="post" action="{{ url('resize/resize_image') }}" enctype="multipart/form-data">
    @CSRF
    <div class="row">
           <div class="col-md-4" align="right">
            <h3>Select Image</h3>
           </div>
           <div class="col-md-4">
            <br />
               <input type="file" name="image" class="image" />
           </div>
           <div class="col-md-2">
            <br />
               <button type="submit" class="btn btn-success">Upload Image</button>
           </div>
       </div>
   </form>
   <br />
   @if(count($errors) > 0)
    <div class="alert alert-danger">
           <ul>
           @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
           @endforeach
           </ul>
       </div>
   @endif

   @if($message = Session::get('success'))
   <div class="alert alert-success alert-block">
       <button type="button" class="close" data-dismiss="alert">×</button>    
       <strong>{{ $message }}</strong>
   </div>
   <div class="row">
       <div class="col-md-6">
           <strong>Original Image:</strong>
           <br/>
           <img src="/images/{{ Session::get('original_image') }}" class="img-responsive img-thumbnail">
       </div>
       <div class="col-md-4">
           <strong>Resized Images:</strong>
           <br/>
           @foreach(Session::get('resized_images')  as $image)
           <img src="/resized_images/{{ $image }}" class="img-thumbnail" />
           @endforeach
       </div>
   </div>
   @endif
  </div>
 </body>
</html>