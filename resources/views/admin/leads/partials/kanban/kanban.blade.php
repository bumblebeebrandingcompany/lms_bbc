<div class="container-fluid h-100">
    @foreach ($lead_stages as $stage => $lead_stage)
        @php
            if ($stage == 'no_stage') {
                $leads = $stage_wise_leads[''] ?? [];
            } else {
                $leads = $stage_wise_leads[$stage] ?? [];
            }
        @endphp
        @if (!empty($leads))
            <div class="card card-row {{ $lead_stage['class'] ?? 'card-secondary' }}">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $lead_stage['title'] ?? ucfirst(str_replace('_', ' ', $stage)) }}
                    </h3>
                </div>
                <div class="card-body">
                    @forelse($leads as $lead)
                        <div
                            class="card card-outline {{ $lead_stage['class'] ?? 'card-secondary' }}">
                            <div class="card-header">
                                <h5 class="card-title">
                                    {{ ucfirst($lead->name ?? '') }}
                                    <small>
                                        {!! $lead->ref_num ? '(<code>' . $lead->ref_num . '</code>)' : '' !!}
                                    </small>
                                </h5>
                                <div class="card-tools">
                                    <input class="form-check-input lead_ids" type="checkbox" id="{{ $lead->id }}"
                                        value="{{ $lead->id }}">
                                    <a href="{{ route('admin.leads.show', [$lead->id]) }}"
                                        class="btn btn-tool btn-link">
                                        <i class="far fa-eye text-primary"></i>
                                    </a>
                                    @if (auth()->user()->is_superadmin)
                                        <a href="{{ route('admin.leads.edit', [$lead->id]) }}" class="btn btn-tool">
                                            <i class="far fa-edit text-info"></i>
                                        </a>
                                        <form action="{{ route('admin.leads.destroy', [$lead->id]) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="btn btn-tool">
                                                <i class="far fa-trash-alt text-danger"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        {{-- <strong>@lang('messages.phone')</strong>: --}}
                                        @if (auth()->user()->is_agency && !empty($lead->phone))
                                            {{ maskNumber($lead->phone) }}
                                        @else
                                            {{ $lead->phone ?? '' }}
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        {{-- <strong>@lang('messages.email')</strong>: --}}
                                        @if (auth()->user()->is_agency && !empty($lead->email))
                                            {{ maskEmail($lead->email) }}
                                        @else
                                            {{ $lead->email ?? '' }}
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <strong> {{ trans('messages.child_name') }}
                                        </strong>:
                                        {{ $lead->child_name ?? '' }}
                                    </div>
                                    <div class="col-md-12">
                                        <strong> {{ trans('messages.grade_enquired') }}
                                        </strong>:
                                        {{ $lead->grade_enquired ?? '' }}
                                    </div>

                                    <div id="additional-details-{{ $lead->id }}"
                                        class="additional-details-container">
                                        <!-- <div class="col-md-12">
                                        <strong>@lang('messages.status')</strong>:
                                        {{ $lead->sell_do_status }}
                                    </div> -->
                                        <div class="col-md-12">
                                            <strong>{{ trans('cruds.lead.fields.campaign') }}</strong>:
                                            {{ $lead->campaign->campaign_name ?? '' }}
                                        </div>
                                        <div class="col-md-12">
                                            <strong>{{ trans('messages.source') }}</strong>:
                                            {{ $lead->source->name ?? '' }}
                                        </div>
                                        <div class="col-md-12">
                                            <strong> {{ trans('messages.intake_year') }}
                                            </strong>:
                                            <span class="display-value">{{ $lead->intake_year ?? '' }}</span>
                                        </div>
                                        <div class="col-md-12">
                                            <strong></strong>:
                                            {{ $lead->project->name ?? '' }}
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Application No</strong>:
                                            {{ $lead->application_no ?? '' }}
                                        </div>
                                        <div class="col-md-12">
                                            <strong>Front Office</strong>:

                                                {{$lead->application->user->representative_name ?? 'not updated'}}

                                        </div>
                                        <div class="col-md-12">
                                            <strong>Admission Team</strong>:

                                                {{ $lead->application->users->representative_name ?? 'not updated' }}
                                        </div>

                                        <div class="col-md-12">
                                            <strong>{{ trans('messages.cp_comments') }}</strong>:
                                            {{ $lead->cp_comments ?? '' }}
                                        </div>

                                        <div class="col-md-12">
                                            <strong>@lang('messages.created_at')</strong>:
                                            @if (!empty($lead->created_at))
                                                {{ @format_datetime($lead->created_at) }}
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Add more details as needed -->
                                </div>
                                <div class="col-md-12 text-center"> <!-- Center the button within its parent -->
                                    <button class="btn btn-info btn-xs toggle-details-btn"
                                        data-target="{{ $lead->id }}">
                                        <i class="fas fa-plus"></i> <!-- Initial plus icon -->
                                        <i class="fas fa-minus" style="display: none;"></i>
                                        <!-- Minus icon initially hidden -->
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    @lang('messages.no_leads_found')
                                </h5>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    @endforeach
</div>
