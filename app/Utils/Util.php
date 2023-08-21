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
            $cp_project_ids = $user->project_assigned ?? [];
            $query = $query->where(function ($q) use($user, $cp_project_ids) {
                        if($user->is_channel_partner) {
                            $q->whereIn('id', $cp_project_ids);
                        } else {
                            $q->where('created_by_id', $user->id)
                                ->orWhere('client_id', $user->client_id);
                        }
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
        $name = !empty($source->name_key) ? ($payload[$source->name_key] ?? '') : ($payload['name'] ?? '');
        $email = !empty($source->email_key) ? ($payload[$source->email_key] ?? '') : ($payload['email'] ?? '');
        $additional_email = !empty($source->additional_email_key) ? ($payload[$source->additional_email_key] ?? '') : '';
        $phone = !empty($source->phone_key) ? ($payload[$source->phone_key] ?? '') : ($payload['phone'] ?? '');
        $secondary_phone = !empty($source->secondary_phone_key) ? ($payload[$source->secondary_phone_key] ?? '') : '';

        $lead = Lead::create([
            'source_id' => $source->id,
            'name' => $name ?? '',
            'email' => $email ?? '',
            'additional_email' => $additional_email ?? '',
            'phone' => $phone ?? '',
            'secondary_phone' => $secondary_phone ?? '',
            'project_id' => $source->project_id,
            'campaign_id' => $source->campaign_id,
            'lead_details' => $payload
        ]);

        $this->storeUniqueWebhookFields($lead);

        $response = $this->sendApiWebhook($lead->id);

        return $response;
    }

    // public function sendWebhook($id)
    // {
    //     try {

    //         $lead = Lead::findOrFail($id);
    //         $source = Source::findOrFail($lead->source_id);

    //         if(
    //             !empty($source) &&
    //             !empty($source->outgoing_webhook) &&
    //             !empty($lead) &&
    //             !empty($lead->lead_details)
    //         ) {
    //             foreach ($source->outgoing_webhook as $webhook) {
    //                 if(!empty($webhook['url'])) {
    //                     if(!empty($webhook['secret_key'])) {
    //                         WebhookCall::create()
    //                             ->useSecret($webhook['secret_key'])
    //                             ->useHttpVerb($webhook['method'])
    //                             ->url($webhook['url'])
    //                             ->payload($lead->lead_details)
    //                             ->dispatch();
    //                     }

    //                     if(empty($webhook['secret_key'])) {
    //                         WebhookCall::create()
    //                             ->doNotSign()
    //                             ->useHttpVerb($webhook['method'])
    //                             ->url($webhook['url'])
    //                             ->payload($lead->lead_details)
    //                             ->dispatch();
    //                     }
    //                 }
    //             }
    //         }

    //         $output = ['success' => true, 'msg' => __('messages.success')];
    //     } catch (\Exception $e) {
    //         $output = ['success' => false, 'msg' => __('messages.something_went_wrong')];
    //     }
    //     return $output;
    // }

    public function sendApiWebhook($id)
    {
        $webhook_responses = [];
        $request_body = null;
        $lead = Lead::findOrFail($id);
        $project = Project::findOrFail($lead->project_id);

        //setting this to save sell do response only once in DB for a lead
        $is_sell_do_executed = false;

        try {

            if(
                !empty($project) &&
                !empty($project->outgoing_apis) &&
                !empty($lead)
            ) {
                $sell_do_response = !empty($lead->sell_do_response) ? json_decode($lead->sell_do_response, true) : [];
                foreach ($project->outgoing_apis as $api) {
                    $headers = !empty($api['headers']) ? json_decode($api['headers'], true) : [];
                    $request_body = $this->replaceTags($lead, $api);
                    if(!empty($api['url'])) {
                        $headers['secret-key'] = $api['secret_key'] ?? '';
                        $constants = $this->getApiConstants($api);
                        $request_body = array_merge($request_body, $constants);

                        //merge query parameter into request body
                        $queryString = parse_url($api['url'], PHP_URL_QUERY);
                        parse_str($queryString, $queryArray);
                        $request_body = array_merge($request_body, $queryArray);

                        $response = $this->postWebhook($api['url'], $api['method'], $headers, $request_body);

                        //checking this to save sell.do response only once in DB for a lead
                        // or update if any error
                        if(
                            (
                                !$is_sell_do_executed && 
                                empty($sell_do_response)
                            ) || 
                            (
                                !$is_sell_do_executed && 
                                !empty($sell_do_response) &&
                                !empty($sell_do_response['error'])
                            )
                        ){
                            if (strpos($api['url'], 'app.sell.do') !== false) {
                                if(!empty($response['sell_do_lead_id'])){

                                    $lead->sell_do_is_exist = isset($response['selldo_lead_details']['lead_already_exists']) ? $response['selldo_lead_details']['lead_already_exists'] : false;

                                    $lead->sell_do_lead_created_at = isset($response['selldo_lead_details']['lead_created_at']) ? $response['selldo_lead_details']['lead_created_at'] : null;

                                    $lead->sell_do_lead_id = isset($response['sell_do_lead_id']) ? $response['sell_do_lead_id'] : null;

                                    $lead->sell_do_response = json_encode($response);

                                    $lead->save();

                                }
                            }
                        }

                        $webhook_responses[] = [
                            'input' => $request_body,
                            'response' => $response
                        ];
                    }
                }
            }
            $output = ['success' => true, 'msg' => __('messages.success')];
        } catch (RequestException $e) {
            $webhook_responses[] = [
                'input' => $request_body,
                'response' => $e->getMessage()
            ];
            $output = ['success' => false, 'msg' => __('messages.something_went_wrong')];
        }

        /*
        * Save webhook responses
        */
        if(!empty($lead->webhook_response) && is_array($lead->webhook_response)) {
            $webhook_responses =  array_merge($lead->webhook_response, $webhook_responses);
        }
        $lead->webhook_response = $webhook_responses;
        $lead->save();

        return $output;
    }

    public function replaceTags($lead, $api)
    {
        $request_body = $api['request_body'] ?? [];
        if(empty($request_body)) {
            return $lead->lead_details;
        }

        $tag_replaced_req_body = [];
        $source = $lead->source;
        foreach ($request_body as $value) {
            if(!empty($value['key']) && !empty($value['value'])) {
                if(count($value['value']) > 1) {
                    $arr_value = [];
                    foreach ($value['value'] as $field) {
                        if(isset($lead->lead_info[$field]) && !empty($lead->lead_info[$field])) {
                            $arr_value[] = $lead->lead_info[$field];
                        } else {
                            $arr_value[] = $this->getPredefinedValue($field, $lead, $source);
                        }
                    }
                    $empty_replaced_values = array_values(array_filter($arr_value));
                    $tag_replaced_req_body[$value['key']] = implode(' | ', $empty_replaced_values);
                } else {
                    $data_value = '';
                    if(
                        !empty($value['value']) &&
                        !empty($value['value'][0])
                    ) {
                        $data_value = $this->getPredefinedValue($value['value'][0], $lead, $source);
                    }
                    $tag_replaced_req_body[$value['key']] = $lead->lead_info[$value['value'][0]] ?? $data_value;
                }
            }
        }
        return $tag_replaced_req_body;
    }

    public function getPredefinedValue($field, $lead, $source=null)
    {
        if(
            (
                !empty($source->email_key) &&
                !empty($field) &&
                ($source->email_key == $field)
            ) ||
            (
                !empty($field) &&
                !empty($lead->email) &&
                in_array($field, ['email', 'Email', 'EMAIL'])
            )
        ) {
            return $lead->email ?? '';
        } else if(
            (
                !empty($source->phone_key) &&
                !empty($field) &&
                ($source->phone_key == $field)
            ) ||
            (
                !empty($field) &&
                !empty($lead->phone) &&
                in_array($field, ['phone', 'Phone', 'PHONE'])
            )
        ) {
            return $lead->phone ?? '';
        } else if(
            (
                !empty($source->name_key) &&
                !empty($field) &&
                ($source->name_key == $field)
            ) ||
            (
                !empty($field) &&
                !empty($lead->name) &&
                in_array($field, ['name', 'Name', 'NAME'])
            )
        ) {
            return $lead->name ?? '';
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_comments'])
        ) {
            return $lead->comments ?? '';
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_cp_comments'])
        ) {
            return $lead->cp_comments ?? '';
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_created_by'])
        ) {
            return optional($lead->createdBy)->name ?? '';
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_created_at'])
        ) {
            return $lead->created_at ?? '';
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_source_name'])
        ) {
            if(!empty($lead->createdBy) && $lead->createdBy->user_type == 'ChannelPartner'){
                return 'Channel Partner';
            } else {
                return optional($lead->source)->source_name ?? '';
            }
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_campaign_name'])
        ) {
            return optional($lead->campaign)->campaign_name ?? '';
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_agency_name']) &&
            !empty($lead->campaign) &&
            !empty($lead->campaign->agency) &&
            !empty($lead->campaign->agency->name)
            
        ) {
            return $lead->campaign->agency->name ?? '';
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_additional_email']) &&
            !empty($lead->additional_email)
        ) {
            return $lead->additional_email ?? '';
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_secondary_phone']) &&
            !empty($lead->secondary_phone)
        ) {
            return $lead->secondary_phone ?? '';
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_source_field1'])
        ) {
            return optional($lead->source)->source_field1 ?? '';
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_source_field2'])
        ) {
            return optional($lead->source)->source_field2 ?? '';
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_source_field3'])
        ) {
            return optional($lead->source)->source_field3 ?? '';
        } else if(
            !empty($field) && 
            in_array($field, ['predefined_source_field4'])
        ) {
            return optional($lead->source)->source_field4 ?? '';
        }
    }

    public function getLeadTags($id)
    {
        $lead =  Lead::where('source_id', $id)
                    ->latest()
                    ->first();

        $tags = !empty($lead->lead_info) ? array_keys($lead->lead_info) : [];

        return $tags;
    }

    /*
    * return sources
    *
    * @param $for_cp: is channel partner
    *
    * @return array
    */
    public function getSources($for_cp=false)
    {
        $sources = Source::with(['project', 'campaign'])
                    ->get();

        if($for_cp) {
            $sources_arr = [];
            foreach ($sources as $source) {
                $sources_arr[$source->id] = $source->project->name.' | '.$source->campaign->campaign_name.' | '.$source->name;
            }
            return $sources_arr;
        }

        return $sources->pluck('name', 'id')->toArray();
    }

    public function postWebhook($url, $method, $headers=[], $body=[])
    {
        if(in_array($method, ['get'])) {

            $client = new Client();
            $response = $client->get($url, [
                'query' => $body,
                'headers' => $headers,
            ]);
            
            return json_decode($response->getBody(), true);
        }
        if(in_array($method, ['post'])) {

            $client = new Client();
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            return json_decode($response->getBody(), true);
        }
    }

    /*
    * return project dropdown
    *
    * @param $for_cp: is channel partner
    *
    * @return array
    */
    public function getProjectDropdown($for_cp=false)
    {
        $projects = Project::with(['client'])
                    ->get();

        if($for_cp) {
            $projects_arr = [];
            foreach ($projects as $project) {
                $projects_arr[$project->id] = $project->client->name.' | '.$project->name;
            }
            return $projects_arr;
        }

        return $projects->pluck('name', 'id')->toArray();
    }

    public function getApiConstants($api)
    {
        if(isset($api['constants']) && !empty($api['constants'])) {
            $constants = [];
            foreach ($api['constants'] as $value) {
                if(!empty($value['key']) && !empty($value['value'])) {
                    $constants[$value['key']] = $value['value'];
                }
            }
            return $constants;
        }
        return [];
    }

    public function getGlobalClientsFilter()
    {
        $__global_clients_filter = session('__global_clients_filter');

        return $__global_clients_filter ?? [];
    }

    /*
    * return project ids for
    * clients
    *
    * @return array
    */
    public function getClientsProjects($client_ids=[])
    {
        $client_ids = empty($client_ids) ? $this->getGlobalClientsFilter() : $client_ids;
        if(empty($client_ids)) {
            return [];
        }

        $projects = Project::whereIn('client_id', $client_ids)
                    ->pluck('id')->toArray();

        return $projects;
    }

    /*
    * return campaign ids for
    * clients
    *
    * @return array
    */
    public function getClientsCampaigns($client_ids=[])
    {
        $project_ids = $this->getClientsProjects($client_ids);
        
        if (empty($project_ids)) {
            return [];
        }

        $campaign_ids = Campaign::whereIn('project_id', $project_ids)
                        ->pluck('id')->toArray();

        return $campaign_ids;
    }

    public function getWebhookFieldsTags($id)
    {
        $project =  Project::findOrFail($id);

        $db_fields = Lead::DEFAULT_WEBHOOK_FIELDS;
        $tags = !empty($project->webhook_fields) ? array_merge($project->webhook_fields, $db_fields) : $db_fields;

        return array_unique($tags);
    }

    public function storeUniqueWebhookFields($lead)
    {
        $project =  Project::findOrFail($lead->project_id);

        $fields = !empty($lead->lead_info) ? array_keys($lead->lead_info) : [];
        $webhook_fields = !empty($project->webhook_fields) ? array_merge($project->webhook_fields, $fields) : $fields;
        $unique_webhook_fields = array_unique($webhook_fields);
        $project->webhook_fields = array_values($unique_webhook_fields);
        $project->save();
    }

    public function storeUniqueWebhookFieldsWhenCreatingWebhook($project)
    {
        $outgoing_apis = $project->outgoing_apis;
        $fields = [];
        foreach($outgoing_apis as $outgoing_api){
            $body = $outgoing_api['request_body'] ?? [];
            foreach($body as $details){
                $fields[] = $details['key'];
            }
        }
        
        $webhook_fields = !empty($project->webhook_fields) ? array_merge($project->webhook_fields, $fields) : $fields;
        $unique_webhook_fields = array_unique($webhook_fields);
        $project->webhook_fields = array_values($unique_webhook_fields);
        $project->save();
    }

    public function getClientProjects($id)
    {
        $projects = Project::where('client_id', $id)
                    ->pluck('name', 'id')
                    ->toArray();

        return $projects;
    }
}