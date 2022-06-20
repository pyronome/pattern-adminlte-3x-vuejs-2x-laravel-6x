<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ $project_title }}</title>
        <meta name="theme-color" content="#ffffff">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @if (config('app.env') == 'local')
        <link rel="stylesheet" href="{{asset('css/wisilo/app.css')}}">
        @else
        <link rel="stylesheet" href="{{asset(mix('css/wisilo/app.css'), true)}}">
        @endif
        <link rel="stylesheet" href="/css/wisilo/custom.css">
        
        <script>var __publicAssetsURL = "{{ URL::asset('/') }}";</script>
        <script>var __storageURL = "{{ URL::asset('/storage/') }}/";</script>
    </head>