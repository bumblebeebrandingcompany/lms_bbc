<div class="row">
    <div class="col-md-12">
        @if(count($lead_events) > 0)
            <div class="timeline">
                @foreach($lead_events as $index => $event)
                    <div class="time-label">
                        <span class="text-center @if($event->event_type == 'call_feedback_submitted')
                                    bg-primary
                                @elseif($event->event_type == 'sitevisit_scheduled')
                                    bg-warning
                                @elseif($event->event_type == 'sitevisit_conducted')
                                    bg-success
                                @elseif($event->event_type == 'stage_changed')
                                    bg-danger
                                @elseif($event->event_type == 'followup_scheduled')
                                    bg-warning
                                @elseif($event->event_type == 'followup_conducted')
                                    bg-success
                                @elseif($event->event_type == 'note_added')
                                    bg-info
                                @elseif($event->event_type == 'document_sent')
                                    bg-dark
                                @else
                                    bg-olive
                                @endif">
                            {{@format_datetime($event->added_at)}}
                            <br>
                            <small>({{@get_diff_for_humans($event->added_at)}})</small>
                        </span>
                    </div>
                    <div>
                        @if($event->event_type == 'call_feedback_submitted')
                            <i class="fas fa-phone-volume bg-blue"></i>
                        @elseif($event->event_type == 'sitevisit_scheduled')
                            <i class="fas fa-map-marker-alt bg-yellow"></i>
                        @elseif($event->event_type == 'sitevisit_conducted')
                            <i class="fas fa-map-marker bg-green"></i>
                        @elseif($event->event_type == 'stage_changed')
                            <i class="fas fa-exchange-alt bg-red"></i>
                        @elseif($event->event_type == 'followup_scheduled')
                            <i class="fas fa-bullseye bg-yellow"></i>
                        @elseif($event->event_type == 'followup_conducted')
                            <i class="fas fa-certificate bg-green"></i>
                        @elseif($event->event_type == 'note_added')
                            <i class="far fa-sticky-note bg-info"></i>
                        @elseif($event->event_type == 'document_sent')
                            <i class="fas fa-file-alt bg-dark"></i>
                        @else
                            <i class="far fa-check-circle bg-olive"></i>
                        @endif
                        <div class="timeline-item">
                            <span class="time">
                                <i class="fas fa-clock"></i>
                                {{@format_time($event->added_at)}}
                            </span>
                            <h3 class="timeline-header text-bold @if($event->event_type == 'call_feedback_submitted')
                                    text-primary
                                @elseif($event->event_type == 'sitevisit_scheduled')
                                    text-warning
                                @elseif($event->event_type == 'sitevisit_conducted')
                                    text-success
                                @elseif($event->event_type == 'stage_changed')
                                    text-danger
                                @elseif($event->event_type == 'followup_scheduled')
                                    text-warning
                                @elseif($event->event_type == 'followup_conducted')
                                    text-success
                                @elseif($event->event_type == 'note_added')
                                    text-info
                                @elseif($event->event_type == 'document_sent')
                                    text-dark
                                @else
                                    text-olive
                                @endif">
                                {{ucfirst(str_replace('_', ' ', $event->event_type))}}
                                <small class="text-muted">
                                    (
                                        {{ucfirst(str_replace('_', ' ', $event->source))}}
                                    )
                                </small>
                            </h3>
                            <div class="timeline-body">
                            @if($event->event_type == 'call_feedback_submitted')
                                @includeIf('admin.leads.partials.event_details.call_feedback_submitted')
                            @elseif($event->event_type == 'sitevisit_scheduled')
                                @includeIf('admin.leads.partials.event_details.sitevisit')
                            @elseif($event->event_type == 'sitevisit_conducted')
                                @includeIf('admin.leads.partials.event_details.sitevisit')
                            @elseif($event->event_type == 'stage_changed')
                                @includeIf('admin.leads.partials.event_details.stage_changed')
                            @elseif($event->event_type == 'followup_scheduled')
                                @includeIf('admin.leads.partials.event_details.followup')
                            @elseif($event->event_type == 'followup_conducted')
                                @includeIf('admin.leads.partials.event_details.followup')
                            @elseif($event->event_type == 'note_added')
                                @includeIf('admin.leads.partials.event_details.note_added')
                            @elseif($event->event_type == 'document_sent')
                                @includeIf('admin.leads.partials.event_details.document_sent')
                            @else
                                {{json_encode($event->webhook_data ?? [])}}
                            @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="callout callout-warning">
                <h5>No, record found.</h5>
            </div>
        @endif
    </div>
</div>