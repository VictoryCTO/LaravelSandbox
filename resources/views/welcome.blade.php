@extends('layouts.app')

@section('content')
  Welcome to the ImageResizer demo page.<br/>
  To test out the image resizing service, please upload an image file.<br/>
  
  @include('upload_form')
  @include('recent_list')
@endsection
