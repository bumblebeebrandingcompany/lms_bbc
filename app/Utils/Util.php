<?php

namespace App\Utils;
use App\Models\Campaign;
use App\Models\Project;
use App\Models\Lead;
use Illuminate\Support\Str;
use Spatie\WebhookServer\WebhookCall;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\Source;
class Util
{
    public function getUserProjects($user)
    {
        $query = new Project();

        if (!$user->is_superadmin) {
            $query = $query->where(function ($q) use($user) {
                        $q->where('created_by_id', $user->id)
                        ->orWhere('client_id', $user->client_id);
                    });
        }

        $project_ids = $query->pluck('id')->toArray();

        return $project_ids;
    }

    public function getCampaigns($user, $project_ids=[])
    {
        $query = new Campaign();

        if (!$user->is_superadmin && $user->is_agency) {
            $query = $query->where(function ($q) use($user) {
                        $q->where('agency_id', $user->agency_id);
                    });
        }

        if (!$user->is_superadmin && $user->is_client) {
            $query = $query->where(function ($q) use($project_ids) {
                    $q->whereIn('project_id', $project_ids);
                });
        }

        $campaign_ids = $query->pluck('id')->toArray();
        
        return $campaign_ids;
    }

    public function generateWebhookSecret()
    {
        $webhookSecret = (string)Str::uuid();
        return $webhookSecret;
    }
    
    public function createLead($source, $payload)
    {
        $email = $payload[$source->email_key] ?? '';
        $phone = $payload[$source->phone_key] ?? '';
        
        $lead = Lead::create([
            'source_id' => $source->id,
            'email' => $email ?? '',
            'phone' => $phone ?? '',
            'project_id' => $source->project_id,
            'campaign_id' => $source->campaign_id,
            'lead_details' => $payload
        ]);
        
        // $this->sendWebhook($lead->id);
        $this->sendApiWebhook($lead->id);

        return $lead;
    }

    public function sendWebhook($id)
    {
        try {

            $lead = Lead::findOrFail($id);
            $source = Source::findOrFail($lead->source_id);

            if(
                !empty($source) &&
                !empty($source->outgoing_webhook) &&
                !empty($lead) &&
                !empty($lead->lead_details)
            ) {
                foreach ($source->outgoing_webhook as $webhook) {
                    if(!empty($webhook['url'])) {
                        if(!empty($webhook['secret_key'])) {
                            WebhookCall::create()
                                ->useSecret($webhook['secret_key'])
                                ->useHttpVerb($webhook['method'])
                                ->url($webhook['url'])
                                ->payload($lead->lead_details)
                                ->dispatch();
                        }

                        if(empty($webhook['secret_key'])) {
                            WebhookCall::create()
                                ->doNotSign()
                                ->useHttpVerb($webhook['method'])
                                ->url($webhook['url'])
                                ->payload($lead->lead_details)
                                ->dispatch();
                        }
                    }
                }
            }

            $output = ['success' => true, 'msg' => __('messages.success')];
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => __('messages.something_went_wrong')];
        }
        return $output;
    }

    public function sendApiWebhook($id)
    {
        try {

            $lead = Lead::findOrFail($id);
            $source = Source::findOrFail($lead->source_id);

            if(
                !empty($source) &&
                !empty($source->outgoing_apis) &&
                !empty($lead) &&
                !empty($lead->lead_details)
            ) {
                foreach ($source->outgoing_apis as $api) {
                    $headers = !empty($api['headers']) ? json_decode($api['headers'], true) : [];
                    $request_body = $this->replaceTags($lead, $api['request_body']);
                    if(!empty($api['url'])) {
                        $headers['secret-key'] = $api['secret_key'] ?? '';
                        if(in_array($api['method'], ['get'])) {
                            $client = new Client();
                            $response = $client->get($api['url'], [
                                'query' => $request_body,
                                'headers' => $headers,
                            ]);
                            //Response check
                            // $data = json_decode($response->getBody(), true);
                        }
                        if(in_array($api['method'], ['post'])) {
                            $client = new Client();
                            $response = $client->post($api['url'], [
                                'headers' => $headers,
                                'json' => $request_body,
                            ]);

                            //Response check
                            // $data = json_decode($response->getBody(), true);
                        }
                    }
                }
            }
            $output = ['success' => true, 'msg' => __('messages.success')];
        } catch (RequestException $e) {
            $output = ['success' => false, 'msg' => __('messages.something_went_wrong')];
        }
        return $output;
    }

    public function replaceTags($lead, $request_body)
    {
        if(empty($request_body)) {
            return $lead->lead_details;
        }

        $tag_replaced_req_body = [];
        foreach ($request_body as $value) {
            if(!empty($value['key']) && !empty($value['value'])) {
                if(count($value['value']) > 1) {
                    $arr_value = [];
                    foreach ($value['value'] as $field) {
                        if(isset($lead->lead_info[$field]) && !empty($lead->lead_info[$field])) {
                            $arr_value[] = $lead->lead_info[$field];
                        }
                    }
                    $tag_replaced_req_body[$value['key']] = implode(', ', $arr_value);
                } else {
                    $tag_replaced_req_body[$value['key']] = $lead->lead_info[$value['value'][0]] ?? '';
                }
            }
        }

        return $tag_replaced_req_body;
    }

    public function getLeadTags($id)
    {
        $lead =  Lead::where('source_id', $id)
                    ->latest()
                    ->first();

        $tags = !empty($lead->lead_info) ? array_keys($lead->lead_info) : [];

        return $tags;
    }
}