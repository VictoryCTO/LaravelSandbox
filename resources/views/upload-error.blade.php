@extends('layouts.app')

@section('content')
    <h3>Uh oh! Something went wrong with your upload!</h3>
    <div class="col-md-12">

        <h4>The error was:</h4>
        <pre>{!! $errorMessage !!}</pre>
        <h4>Don't worry, I've reported it, and someone will take care if it right away!</h4>
    </div>
@endsection

