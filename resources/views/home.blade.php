@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row mb-2">
        <div class="col-sm-6">
                <h2>
                    Dashboard
                </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary card-outline">
               <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        @if(auth()->user()->is_superadmin)
                            <div class="{{ $settings1['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings1['chart_title'] }}</span>
                                        <span class="info-box-number">{{ number_format($settings1['total_number']) }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        @endif
                        @if(auth()->user()->is_superadmin)
                            <div class="{{ $settings2['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary" >
                                        <i class="fas fa-user-friends"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings2['chart_title'] }}</span>
                                        <span class="info-box-number">{{ number_format($settings2['total_number']) }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        @endif
                        @if(auth()->user()->is_superadmin)
                            <div class="{{ $settings3['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success" >
                                        <i class="fas fa-user-friends"></i>
                                    </span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings3['chart_title'] }}</span>
                                        <span class="info-box-number">{{ number_format($settings3['total_number']) }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        @endif
                        @if(auth()->user()->is_superadmin)
                            <div class="{{ $settings4['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning" >
                                        <i class="fas fa-user-friends"></i>
                                    </span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings4['chart_title'] }}</span>
                                        <span class="info-box-number">{{ number_format($settings4['total_number']) }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        @endif
                        @if(auth()->user()->is_superadmin)
                            <div class="{{ $settings5['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger" >
                                        <i class="fa fa-chart-line"></i>
                                    </span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings5['chart_title'] }}</span>
                                        <span class="info-box-number">{{ number_format($settings5['total_number']) }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        @endif
                        @if(!auth()->user()->is_agency)
                            <div class="{{ $settings6['column_class'] }}">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info" >
                                        <i class="fas fa-project-diagram"></i>
                                    </span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $settings6['chart_title'] }}</span>
                                        <span class="info-box-number">{{ number_format($settings6['total_number']) }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        @endif
                        <div class="{{ $settings7['column_class'] }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary" >
                                    <i class="fas fa-bullhorn"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $settings7['chart_title'] }}</span>
                                    <span class="info-box-number">{{ number_format($settings7['total_number']) }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="{{ $settings8['column_class'] }}">
                            <div class="info-box">
                                <span class="info-box-icon bg-success" >
                                    <i class="fas fa-user-plus"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $settings8['chart_title'] }}</span>
                                    <span class="info-box-number">{{ number_format($settings8['total_number']) }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        {{-- Widget - latest entries --}}
                        <div class="{{ $settings9['column_class'] }} mt-3">
                            <h4>{{ $settings9['chart_title'] }}</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            @foreach($settings9['fields'] as $key => $value)
                                                <th>
                                                    {{ trans(sprintf('cruds.%s.fields.%s', $settings9['translation_key'] ?? 'pleaseUpdateWidget', $key)) }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($settings9['data'] as $entry)
                                            <tr>
                                                @foreach($settings9['fields'] as $key => $value)
                                                    <td>
                                                        @if($value === '')
                                                            {{ $entry->{$key} }}
                                                        @elseif(is_iterable($entry->{$key}))
                                                            @foreach($entry->{$key} as $subEentry)
                                                                <span class="label label-info">{{ $subEentry->{$value} }}</span>
                                                            @endforeach
                                                        @else
                                                            {{ data_get($entry, $key . '.' . $value) }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="{{ count($settings9['fields']) }}">{{ __('No entries found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Widget - latest entries --}}
                        <div class="{{ $settings10['column_class'] }} mt-3">
                            <h4>{{ $settings10['chart_title'] }}</h4>
                                <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            @foreach($settings10['fields'] as $key => $value)
                                                <th>
                                                    {{ trans(sprintf('cruds.%s.fields.%s', $settings10['translation_key'] ?? 'pleaseUpdateWidget', $key)) }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($settings10['data'] as $entry)
                                            <tr>
                                                @foreach($settings10['fields'] as $key => $value)
                                                    <td>
                                                        @if($key == 'lead_details')
                                                            @php
                                                                $lead_arr = $entry->{$key};
                                                            @endphp
                                                            @foreach($lead_arr as $key => $value)
                                                                {!!$key!!} : {!!$value!!} <br>
                                                            @endforeach
                                                        @elseif($value === '')
                                                            {{ $entry->{$key} }}
                                                        @elseif(is_iterable($entry->{$key}))
                                                            @foreach($entry->{$key} as $subEentry)
                                                                <span class="label label-info">{{ $subEentry->{$value} }}</span>
                                                            @endforeach
                                                        @else
                                                            {{ data_get($entry, $key . '.' . $value) }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="{{ count($settings10['fields']) }}">{{ __('No entries found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if(auth()->user()->is_superadmin)
                            <div class="{{ $chart11->options['column_class'] }} mt-3">
                                <h3>{!! $chart11->options['chart_title'] !!}</h3>
                                {!! $chart11->renderHtml() !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>{!! $chart11->renderJs() !!}
@endsection