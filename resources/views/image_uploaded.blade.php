@extends('layouts.app')

@section('content')
  Your image has been processed.<br/>
  <img src="/storage/{{ $image_resource->getFname() }}" style="max-width: 800px; max-height: 400px;" />
  <br/>
  @include('upload_form')
  @include('recent_list')
@endsection
