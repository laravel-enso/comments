@extends('laravel-enso/core::emails.layouts.main')

@section('css')
    <style>
        code {
            background: #F8F8FF;
            padding: 6px
        }
    </style>
@endsection

@section('content')
    <p>
        {{$intro}}
    </p>

    <p>
        <code>
            {{$messageBody}}
        </code>
    </p>

    <p>
        {{$action}}
    </p>

    <p>
        {{$ending}}
    </p>
@endsection

@section('buttons')
    <a href="{{$appURL}}" style="background-color:#f5774e;color:#ffffff;display:inline-block;font-family:'Source Sans Pro', Helvetica, Arial, sans-serif;font-size:18px;font-weight:400;line-height:45px;text-align:center;text-decoration:none;width:220px;-webkit-text-size-adjust:none;">{{$appName}}</a>
@endsection