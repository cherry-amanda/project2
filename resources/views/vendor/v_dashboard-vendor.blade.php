@extends('layout.v_template3')
@include('layout.v_nav3')

@section('page')
@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Dashboard Vendor
        </div>
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                @if (session('pesan'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Success</h5>
                        {{ session('pesan') }}
                    </div>
                @endif
           
    </div>
</main>


@endsection
