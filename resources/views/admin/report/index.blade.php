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
                                </div>
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
                                </div>
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
                                </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
