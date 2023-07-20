<?php

namespace App\Utils;
use App\Models\Campaign;
use App\Models\Project;
use App\Models\Lead;
use Illuminate\Support\Str;
use Spatie\WebhookServer\WebhookCall;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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
    
    public function createLead($campaign, $payload)
    {
        $lead = Lead::create([
            'project_id' => $campaign->project_id,
            'campaign_id' => $campaign->id,
            'lead_details' => $payload
        ]);
        
        $this->sendWebhook($lead->id);
        $this->sendApiWebhook($lead->id);

        return $lead;
    }

    public function sendWebhook($id)
    {
        try {

            $lead = Lead::findOrFail($id);
            $campaign = Campaign::findOrFail($lead->campaign_id);

            if(
                !empty($campaign) &&
                !empty($campaign->outgoing_webhook) &&
                !empty($lead) &&
                !empty($lead->lead_details)
            ) {
                foreach ($campaign->outgoing_webhook as $webhook) {
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
            $campaign = Campaign::findOrFail($lead->campaign_id);

            if(
                !empty($campaign) &&
                !empty($campaign->outgoing_apis) &&
                !empty($lead) &&
                !empty($lead->lead_details)
            ) {
                foreach ($campaign->outgoing_apis as $api) {
                    $headers = !empty($api['headers']) ? json_decode($api['headers'], true) : [];
                    if(!empty($api['url'])) {
                        $headers['secret-key'] = $api['secret_key'] ?? '';
                        if(in_array($api['method'], ['get'])) {
                            $client = new Client();
                            $response = $client->get($api['url'], [
                                'query' => $lead->lead_details,
                                'headers' => $headers,
                            ]);
                            //Response check
                            // $data = json_decode($response->getBody(), true);
                        }
                        if(in_array($api['method'], ['post'])) {
                            $client = new Client();
                            $response = $client->post($api['url'], [
                                'headers' => $headers,
                                'json' => $lead->lead_details,
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
}