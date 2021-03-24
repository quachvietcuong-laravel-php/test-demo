<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('admin-title')</title>
        <base href="{{ asset('') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        @include('admin.layouts.partials.style')
    </head>
    
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">