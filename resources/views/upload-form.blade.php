@extends('layouts.app')

@section('content')
    <div class="col-md-6">
        <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="key_name">Give your image a name:</label>
                <input type="text" class="form-control" id="key_name" name="key_name">
            </div>
            <div class="form-group">
                <label for="upload_image">Your file goes here:</label>
                <input type="file" class="form-control" id="upload_image" name="upload_image">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
@endsection
