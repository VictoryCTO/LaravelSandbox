@extends('layouts.app')

@section('content')
  @if (!empty($featured_resource))
    <img src="/cloud_image/{{ $featured_resource->resource_key }}" style="max-width: 600px; max-height: 300px;" />
    <br/>
  @endif
  
  @foreach ($messages as $message)
    {{ $message }} <br/>
  @endforeach
  
  @include('upload_form')
  @include('recent_list')
@endsection
