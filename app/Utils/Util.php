<?php

namespace App\Utils;
use App\Models\Campaign;
use App\Models\Project;
use App\Models\Lead;
use Illuminate\Support\Str;
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
        // $webhookSecret = Str::random(32);
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
        
        return $lead;
    }
}