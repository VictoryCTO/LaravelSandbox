@extends('layouts.default');

@section('content')
<form action="create" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image" id="image">
    <button type="submit">Upload</button>
</form>
@endsection
