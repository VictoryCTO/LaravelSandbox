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

    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-3" style="margin-left: auto;margin-right: auto;">
            <img src="http://victory.s3.amazonaws.com/{{$image->id}}" style="max-height:400px;">
        </div>
    </div> <!-- .row -->
    <div class="row pt-5">

        <div class="col-sm-8" style="margin-left: auto;margin-right: auto;">
            @if (count($image->thumbnails ?? []) > 0)
                @php
                    $count = 0;
                @endphp
                @foreach ($image->thumbnails as $thumbnail)
                    @if($count % 3 == 0)
                        <div class="row">
                            @endif
                            <div class="col-md-4">
                                <div class="thumbnail">
                                    <a href="">
                                        <div class="caption">
                                            <p>{{ $thumbnail->name }}</p>
                                        </div>
                                        <img src="http://victory.s3.amazonaws.com/{{$thumbnail->id}}" alt="{{ $thumbnail->name }}">
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

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
</body>
</html>
