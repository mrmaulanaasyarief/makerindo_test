@extends('master')

@section('content')
  <h4>Welcome <b>{{Auth::user()->name}}</b>.</h4>
@endsection