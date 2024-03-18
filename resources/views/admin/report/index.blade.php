@extends('layouts.admin')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="content">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2>
                    Report
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="row">
                            @if (auth()->user()->is_superadmin)
                                <div class="{{ $settings1['column_class'] }}">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $settings1['chart_title'] }}</span>
                                            <span
                                                class="info-box-number">{{ number_format($settings1['total_number']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (auth()->user()->is_superadmin)
                                <div class="{{ $settings2['column_class'] }}">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary">
                                            <i class="fas fa-user-friends"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $settings2['chart_title'] }}</span>
                                            <span
                                                class="info-box-number">{{ number_format($settings2['total_number']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (auth()->user()->is_superadmin)
                                <div class="{{ $settings4['column_class'] }}">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning">
                                            <i class="fas fa-user-friends"></i>
                                        </span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $settings4['chart_title'] }}</span>
                                            <span
                                                class="info-box-number">{{ number_format($settings4['total_number']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (auth()->user()->is_superadmin)
                                <div class="{{ $settings3['column_class'] }}">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success">
                                            <i class="fas fa-user-friends"></i>
                                        </span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $settings3['chart_title'] }}</span>
                                            <span
                                                class="info-box-number">{{ number_format($settings3['total_number']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (auth()->user()->is_superadmin)
                                <div class="{{ $settings5['column_class'] }}">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-danger">
                                            <i class="fa fa-chart-line"></i>
                                        </span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $settings5['chart_title'] }}</span>
                                            <span
                                                class="info-box-number">{{ number_format($settings5['total_number']) }}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                            @endif
                            @if (!auth()->user()->is_agency)
                                <div class="{{ $settings6['column_class'] }}">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-project-diagram"></i>
                                        </span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $settings6['chart_title'] }}</span>
                                            <span
                                                class="info-box-number">{{ number_format($settings6['total_number']) }}</span>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container">
        <div class="row">
            {{-- <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Campaign Leads Bar Chart</h5>
                        <canvas id="campaignBarChart" width="100" height="100"></canvas>
                    </div>
                </div>
            </div> --}}
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Campaign Leads Pie Chart</h5>
                        <canvas id="campaignPieChart" width="100" height="100"></canvas>
                    </div>
                </div>
            </div>


            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Q's Consolidation</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>BBC</th>
                                        <th>Direct</th>
                                        <th>Overall</th>
                                        <th>Existing Student</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Walkin</td>
                                        <td>{{ number_format($settings5['total_number']) }}</td>
                                        <td>{{ number_format($settings6['total_number']) }}</td>
                                        <td>{{ number_format($settings5['total_number'] + $settings6['total_number']) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Application</td>
                                        <td>{{ number_format($settings10['total_number']) }}</td>
                                        <td>{{ number_format($settings11['total_number']) }}</td>
                                        <td>{{ number_format($settings10['total_number'] + $settings11['total_number']) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Admission</td>
                                        <td>{{ number_format($settings12['total_number']) }}</td>
                                        <td>{{ number_format($settings13['total_number']) }}</td>
                                        <td>{{ number_format($settings12['total_number'] + $settings13['total_number']) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Withdrawal</td>
                                        <td>{{ number_format($settings14['total_number']) }}</td>
                                        <td>{{ number_format($settings15['total_number']) }}</td>
                                        <td>{{ number_format($settings14['total_number'] + $settings15['total_number']) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Application Team Conversion</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>walkin</th>
                                        <th>Application</th>
                                        <th>conversion %</th>
                                    </tr>
                                </thead>


                                @php
                                    $totalLeads = 0;
                                    $totalApplications = 0;
                                @endphp

                                @foreach ($users->where('user_type', 'Frontoffice') as $user)
                                    <tr>
                                        <td>{{ $user->representative_name }}</td>
                                        <td>{{ $user->leads->count() }}</td>
                                        <td>{{ $user->applications->count() }}</td>
                                        <td>
                                            @if ($user->leads->count() > 0 && $user->applications->count() > 0)
                                                {{ number_format(($user->applications->count() / $user->leads->count()) * 100, 2) }}%
                                            @else
                                                0
                                            @endif
                                        </td>
                                    </tr>

                                    @php
                                        $totalLeads += $user->leads->count();
                                        $totalApplications += $user->applications->count();
                                    @endphp
                                @endforeach

                                <tr>
                                    <td>Total</td>
                                    <td>{{ $totalLeads }}</td>
                                    <td>{{ $totalApplications }}</td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Admission Team Conversion</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Admission Team Member</th>
                                        <th>Total attended</th>
                                        <th>Admitted</th>
                                        <th>conversion %</th>
                                    </tr>
                                </thead>


                                @php
                                    $totalLeads = 0;
                                    $totalApplications = 0;
                                @endphp

                                @foreach ($users->where('user_type', 'Admissionteam') as $user)
                                    <tr>
                                        <td>{{ $user->representative_name }}</td>
                                        <td>{{ $user->admissions->count() }}</td>
                                        <td>{{ $user->applications->count() }}</td>
                                        <td>
                                            @if ($user->leads->count() > 0 && $user->applications->count() > 0)
                                                {{ number_format(($user->applications->count() / $user->leads->count()) * 100, 2) }}%
                                            @else
                                                0
                                            @endif
                                        </td>
                                    </tr>

                                    @php
                                        $totalLeads += $user->leads->count();
                                        $totalApplications += $user->applications->count();
                                    @endphp
                                @endforeach

                                <tr>
                                    <td>Total</td>
                                    <td>{{ $totalLeads }}</td>
                                    <td>{{ $totalApplications }}</td>
                                    <td></td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Overall Conversion Rate in %</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Bumblebee</th>
                                        <th>QMIS</th>
                                    </tr>
                                </thead>

                                <tr>
                                    <td>Walkin - Application</td>
                                    <td>  {{ number_format(($settings10['total_number'] / $settings5['total_number']) * 100, 2) }}%</td>
                                    <td> {{ number_format(($settings11['total_number'] / $settings6['total_number']) * 100, 2) }}%</td>
                                </tr>
                                <tr>
                                    <td>Application - Admission</td>
                                    <td>  {{ number_format(($settings12['total_number'] / $settings10['total_number']) * 100, 2) }}%</td>
                                    <td> {{ number_format(($settings13['total_number'] / $settings11['total_number']) * 100, 2) }}%</td>
                                </tr>
                                  <tr>
                                    <td>Walkin - Admission</td>
                                    <td>  {{ number_format(($settings12['total_number'] / $settings5['total_number']) * 100, 2) }}%</td>
                                    <td> {{ number_format(($settings13['total_number'] / $settings6['total_number']) * 100, 2) }}%</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Assuming you have the dynamic lead counts for campaigns
        var campaignLeads = <?php echo json_encode($campaignLeads); ?>;
        var campaignLabels = <?php echo json_encode($campaignLabels); ?>;

        var ctxPie = document.getElementById('campaignPieChart').getContext('2d');
        // Predefined dark colors
        var darkColors = [
            'rgba(255, 234, 136, 1)', // Dark Blue
            'rgba(255, 129, 83, 1)', // Dark Red
            'rgba(74, 202, 180, 1)', // Dark Magenta
            'rgba(135, 139, 182, 1)' // Yellow (added color)
            // Add more dark colors as needed
        ];

        var darkBorderColors = [
            'rgba(0, 0, 139, 1)', // Dark Blue
            'rgba(139, 0, 0, 1)', // Dark Red
            'rgba(139, 0, 139, 1)', // Dark Magenta
            'rgba(0, 100, 0, 1)'
            // Add more dark colors as needed
        ];

        // Bar Chart


        var pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: campaignLabels,
                datasets: [{
                    data: campaignLeads,
                    backgroundColor: darkColors,
                    borderColor: darkBorderColors,
                    borderWidth: 0.5,
                    barThickness: 20
                }]
            }
        });
    </script>
@endsection
