<div class="container-fluid h-100">
    @foreach($lead_stages as $stage => $lead_stage)               
        <div class="card card-row {{$lead_stage['class'] ?? 'card-secondary'}}">
            <div class="card-header">
                <h3 class="card-title">
                    {{$lead_stage['title'] ?? ucfirst(str_replace('_', ' ', $stage))}}
                </h3>
            </div>
            <div class="card-body">
                @php
                    if($stage == 'no_stage'){
                        $leads = $stage_wise_leads[''] ?? [];
                    } else {
                        $leads = $stage_wise_leads[$stage] ?? [];
                    }
                @endphp
                @forelse($leads as $lead)
                    <div class="card card-outline {{$lead_stage['class'] ?? 'card-secondary'}}">
                        <div class="card-header">
                            <h5 class="card-title">
                                {{ucfirst($lead->name ?? '')}} 
                                <small>
                                    {!!$lead->sell_do_lead_id ? ('(<code>#'.$lead->sell_do_lead_id.'</code>)') : ''!!}
                                </small>
                            </h5>
                            <div class="card-tools">
                                <input class="form-check-input lead_ids" type="checkbox" id="{{$lead->id}}" value="{{$lead->id}}">
                                <a href="{{route('admin.leads.show', [$lead->id])}}" class="btn btn-tool btn-link">
                                    <i class="far fa-eye text-primary"></i>
                                </a>
                                @if(auth()->user()->is_superadmin)
                                    <a href="{{route('admin.leads.edit', [$lead->id])}}" class="btn btn-tool">
                                        <i class="far fa-edit text-info"></i>
                                    </a>
                                    <form action="{{route('admin.leads.destroy', [$lead->id])}}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                                    <strong>@lang('messages.email')</strong>:
                                    @if(auth()->user()->is_channel_partner_manager && !empty($lead->email))
                                        {{ maskEmail($lead->email) }}
                                    @else
                                        {{ $lead->email ?? '' }}
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <strong>@lang('messages.phone')</strong>:
                                    @if(auth()->user()->is_channel_partner_manager && !empty($lead->phone))
                                        {{ maskNumber($lead->phone) }}
                                    @else
                                        {{ $lead->phone ?? '' }}
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <strong>@lang('messages.status')</strong>:
                                    {{$lead->sell_do_status}}
                                </div>
                                <div class="col-md-12">
                                    <strong>{{ trans('cruds.lead.fields.project') }}</strong>:
                                    {{$lead->project->name ?? ''}}
                                </div>
                                <div class="col-md-12">
                                    <strong>{{ trans('cruds.lead.fields.campaign') }}</strong>:
                                    {{$lead->campaign->campaign_name ?? ''}}
                                </div>
                                <div class="col-md-12">
                                    <strong>{{ trans('messages.source') }}</strong>:
                                    {{$lead->source->name ?? ''}}
                                </div>
                                <div class="col-md-12">
                                    <strong>@lang('messages.sell_do_created_date')</strong>:
                                    @if(!empty($lead->sell_do_lead_created_at))
                                        {{@format_datetime($lead->sell_do_lead_created_at)}}
                                    @endif
                                </div>
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
    @endforeach
</div>