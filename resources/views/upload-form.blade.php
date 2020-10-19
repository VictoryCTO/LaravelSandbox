@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
            @csrf
            <input type="text" name="key_name">
            <input type="file" name="upload_image">
            <div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
@endsection
