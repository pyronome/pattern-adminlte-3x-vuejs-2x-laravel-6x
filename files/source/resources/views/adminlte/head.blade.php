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
        <link rel="stylesheet" href="/css/adminlte/app.css">
        <link rel="stylesheet" href="/css/adminlte/custom.css">
        <script>var __publicAssetsURL = "{{ URL::asset('/') }}";</script>
        <script>var __storageURL = "{{ URL::asset('/storage/') }}/";</script>
    </head>