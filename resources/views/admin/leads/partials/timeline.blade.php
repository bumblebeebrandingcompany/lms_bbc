<div class="row">
    <div class="col-md-12">
        @if (count($timelineItems) > 0)
            <div class="timeline">
                @foreach ($timelineItems as $index => $item)
                    <div class="time-label">
                        <span
                            class="text-center @if ($item->activity_type == 'call_feedback_submitted') bg-primary
                                @elseif($item->activity_type == 'Site Visit created')
                                    bg-warning
                                @elseif($item->activity_type == 'sitevisit_conducted')
                                    bg-success
                                @elseif($item->activity_type == 'Stage Changed')
                                    bg-danger
                                @elseif($item->activity_type == 'followup_scheduled')
                                    bg-warning
                                @elseif($item->activity_type == 'followup_conducted')
                                    bg-success
                                @elseif($item->activity_type == 'note_added')
                                    bg-info
                                @elseif($item->activity_type == 'document_sent')
                                    bg-dark
                                @else
                                    bg-olive @endif">
                            {{ @format_datetime($item->created_at) }}
                            <br>
                            <small>({{ @get_diff_for_humans($item->created_at) }})</small>
                        </span>
                    </div>
                    <div>
                        @if ($item->activity_type == 'call_feedback_submitted')
                            <i class="fas fa-phone-alt bg-blue" style="color: blue;"></i>
                        @elseif($item->activity_type == 'Site Visit created')
                            <i class="fas fa-map-marker-alt bg-yellow" style="color: yellow;"></i>
                        @elseif($item->activity_type == 'sitevisit_conducted')
                            <i class="fas fa-check-circle bg-green" style="color: green;"></i>
                        @elseif($item->activity_type == 'Stage Changed')
                            <i class="fas fa-exchange-alt bg-red" style="color: red;"></i>
                        @elseif($item->activity_type == 'followup_scheduled')
                            <i class="fas fa-calendar-alt bg-yellow" style="color: yellow;"></i>
                        @elseif($item->activity_type == 'followup_conducted')
                            <i class="fas fa-check-double bg-green" style="color: green;"></i>
                        @elseif($item->activity_type == 'note_added')
                            <i class="fas fa-sticky-note bg-info" style="color: blue;"></i>
                        @elseif($item->activity_type == 'document_sent')
                            <i class="far fa-file-alt bg-dark" style="color: dark;"></i>
                        @else
                            <i class="fas fa-check-circle bg-olive" style="color: olive;"></i>
                        @endif
                        <div class="timeline-item">
                            <span class="time">
                                <i class="fas fa-clock"></i>
                                {{ @format_time($item->created_at) }}
                            </span>
                            <h3
                                class="timeline-header text-bold @if ($item->activity_type == 'call_feedback_submitted') text-primary
                                @elseif($item->activity_type == 'Site Visit created')
                                    text-warning
                                @elseif($item->activity_type == 'sitevisit_conducted')
                                    text-success
                                @elseif($item->activity_type == 'Stage Changed')
                                    text-danger
                                @elseif($item->activity_type == 'followup_scheduled')
                                    text-warning
                                @elseif($item->activity_type == 'followup_conducted')
                                    text-success
                                @elseif($item->activity_type == 'note_added')
                                    text-info
                                @elseif($item->activity_type == 'document_sent')
                                    text-dark
                                @else
                                    text-olive @endif">
                                {{ ucfirst(str_replace('_', ' ', $item->activity_type)) }}

                            </h3>
                            <div class="timeline-body">
                                @if ($item->activity_type == 'call_feedback_submitted')
                                    @includeIf('admin.leads.partials.event_details.call_feedback_submitted')
                                @elseif($item->activity_type == 'Site Visit created')
                                    @includeIf('admin.leads.partials.event_details.sitevisit')
                                @elseif($item->activity_type == 'Rescheduled')
                                    @includeIf('admin.leads.partials.event_details.sitevisit')
                                @elseif($item->activity_type == 'sitevisit_conducted')
                                    @includeIf('admin.leads.partials.event_details.sitevisit')
                                @elseif($item->activity_type == 'Stage Changed')
                                    @php
                                        preg_match('/\b\d+\b/', $item->description, $matches);
                                        $parentStageId = $matches[0] ?? null;
                                        $parentStageName = App\Models\ParentStage::find($parentStageId)->name ?? 'Unknown';
                                        $description = str_replace($parentStageId, $parentStageName, $item->description);
                                    @endphp
                                    {{ $description }}
                                    @includeIf('admin.leads.partials.event_details.stage_changed')
                                @elseif($item->activity_type == 'followup_scheduled')
                                    @includeIf('admin.leads.partials.event_details.followup')
                                @elseif($item->activity_type == 'followup_conducted')
                                    @includeIf('admin.leads.partials.event_details.followup')
                                @elseif($item->activity_type == 'note_added')
                                    @includeIf('admin.leads.partials.event_details.note_added')
                                @elseif($item->activity_type == 'document_sent')
                                    @includeIf('admin.leads.partials.event_details.document_sent')
                                @else
                                    {{ json_encode($item->webhook_data ?? []) }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="callout callout-warning">
                <h5>No record found.</h5>
            </div>
        @endif
    </div>
</div>
