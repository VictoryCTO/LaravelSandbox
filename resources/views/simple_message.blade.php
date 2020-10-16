@extends('layouts.app')

@section('content')
  {!! $message !!}<br/>
  @if (!empty($next_action_url))
    <a href="{!! $next_action_url !!}">{!! $next_action_label !!}</a>
  @endif
@endsection
