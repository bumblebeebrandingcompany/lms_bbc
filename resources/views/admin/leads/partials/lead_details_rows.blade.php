@php
    $fields = !empty($webhook_fields) ? $webhook_fields : [];
@endphp
@forelse($fields as $field)
    @php
        $value = $lead_details[$field] ?? '';
    @endphp
    @includeIf('admin.leads.partials.lead_detail', ['key' => $field, 'value' => $value, $index = $loop->index, 'set_key_readonly' => true])
@empty
    @includeIf('admin.leads.partials.lead_detail', ['key' => '', 'value' => '', $index = 0])
@endforelse