@extends('layouts.default')

@section('content')
    <a class="btn btn-info" href="{{url('image/create')}}">Upload Image</a>

    <div style="padding-top: 10px">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Full Image</th>
                <th scope="col">Small Image</th>
                <th scope="col">Thumbnail</th>
            </tr>
            </thead>
            <tbody>
            @foreach($images as $image)
                <tr>
                    <th scope="row">{{$image->id}}</th>
                    <td><a href="{{$image->full_url}}" target="_blank">Click</a></td>
                    <td><a href="{{$image->small_url}}" target="_blank">Click</a></td>
                    <td><a href="{{$image->thumbnail_url}}" target="_blank">Click</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
