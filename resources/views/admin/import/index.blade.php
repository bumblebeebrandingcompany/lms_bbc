@extends('layouts.admin')
@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('admin.importfile.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="csv_file" accept=".csv">
    <button type="submit">Upload CSV</button>
</form>

 {{-- <!DOCTYPE html>
<html>
<head>
    <title> Import Excel data to database </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
</head>
<body>

<div class="container">
    <div class="card bg-light mt-3">
        <div class="card-header">
            Import Excel data to database
        </div>
        <div class="card-body">
            <form action="{{ route('admin.importfile.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file" class="form-control" accept=".csv">
                <br>
                <button class="btn btn-success">Import User Data</button>
                {{-- <a class="btn btn-warning" href="{{ route('export') }}">Export User Data</a> --}}
            </form>
        </div>
    </div>
</div>

</body>
</html> --}}
 @endsection

