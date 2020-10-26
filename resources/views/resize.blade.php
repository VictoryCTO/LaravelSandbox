<html>
 <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laravel Test</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
 </head>
 <body>
  <div class="container">
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
   <div class="row">
   <?php
        if(isset($images) && sizeof($images)){
            $no = 0;
            foreach ($images as $image) {
                $no ++;
    ?>
            
                <div class="col-md-4">
                    <strong>{{$image->size}} Image:</strong>
                    <br/>
                    <img src="https://mohsin-works.s3.us-east-2.amazonaws.com/avatar/{{ $image->image }}" class="img-thumbnail"/>
                </div>
            
    <?php
    if($no%3 == 0){
        echo "</div><div class='row'>";
    }
        }
    }
   ?>
   </div>
  </div>
 </body>
</html>