<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ config('adminlte.project_title') }}</title>
        <meta name="theme-color" content="#ffffff">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script>document.write("<link rel='stylesheet' src='/css/adminlte/app.css?v=" + Date.now() + "'>");</script>
        <script>document.write("<link rel='stylesheet' src='/css/adminlte/app.css?v=" + Date.now() + "'>");</script>
        <script>var __publicAssetsURL = "{{ URL::asset('/') }}";</script>
        <script>var __storageURL = "{{ URL::asset('/storage/') }}/";</script>
    </head>