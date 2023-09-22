@extends('layouts.app')
@section('content')
<div class="row p-5">
    <div class="col-md-12">
        <h2 class="text-center text-bold text-primary">
            {{config('app.name', 'LMS')}}
        </h2>
    </div>
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <h3>
                            {!!$document->title!!}
                        </h3>
                    </div>
                    <div class="col-md-12">
                        {!!$document->details!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection