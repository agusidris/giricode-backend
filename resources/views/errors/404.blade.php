@extends('layouts.error', ['title' => 'Error 404'])

@section('content')
    <div class="container-fluid ct-height">
        <div class="row justify-content-center h-100">
            <img class="img-error" src="{{ asset('assets\img\errors\404.svg') }}" alt="404 not found">
        </div>
    </div>

<style>
    .ct-height{
        height: 100vh;
    }
    .img-error {
        width: 35rem;
    }
</style>
@endsection
