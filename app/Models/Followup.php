<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    use HasFactory;

    protected $appends = ['is_superadmin', 'is_agency', 'is_channel_partner', 'is_channel_partner_manager','is_presales','is_frontoffice'];

    public $table = 'followups';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'followup_date',
        'followup_time',
        'notes'
    ];
    // public function users()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    protected $fillable = [
        'stage_id'
    ];
    public function logTimeline($lead,$description, $activityType = null,$followup)
    {


        $data = [
            'lead_id'=> $lead,
            'description' => $description,
            'follow_up_id'=>$followup,
        ];

        if ($activityType !== null) {
            $data['activity_type'] = $activityType;
        }

        $this->timeline()->create($data);
    }
    public function timeline()
    {
        return $this->hasMany(LeadTimeline::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
