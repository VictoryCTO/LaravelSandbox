<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel S3</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <style>
            body, .card{
                background: #ededed;
            }
            .thumbnail a img {
                height:156.73px;
                width:100%;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <div class="row pt-5">
            <div class="col-sm-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-info">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <p>{{ Session::get('success') }}</p>
                    </div>
                @endif
            </div>
            <div class="col-sm-8"  style="margin-left: auto;margin-right: auto;">
                @if (count($images ?? []) > 0)
                    @php
                        $count = 0;
                    @endphp
                    <h2>Click image for thumbnails</h2>
                    @foreach ($images as $image)
                        @if($count % 3 == 0)
                            <div class="row">
                                @endif
                                <div class="col-md-4">
                                    <div class="thumbnail">
                                        <a href="{{route('images.show', $image->id)}}">
                                            <div class="caption">
                                                <p>{{ $image->name }}</p>
                                            </div>
                                            <img src="http://victory.s3.amazonaws.com/{{$image->id}}" alt="{{ $image->name }}">
                                        </a>
                                    </div> <!-- .thumbnail -->
                                </div> <!-- .col-md-4 -->
                                @if($count++ % 3 == 2)
                            </div> <!-- .row -->
                        @endif
                    @endforeach
                @else
                    <p>Nothing found</p>
                @endif
            </div>

            <div class="col-sm-4" style="padding-top: 25px;">
                <div class="card border-0 text-center">
                    <form action="{{ url('/images') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="file" name="images[]" id="images" multiple>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    </body>
</html>
