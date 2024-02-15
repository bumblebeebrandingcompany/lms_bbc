<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{

    use HasFactory;

    protected $appends = ['is_superadmin', 'is_client', 'is_agency', ];

    public $table = 'site_visits';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',

    ];
    protected $casts = [
        'follow_up_date',
        'follow_up_time',
        'notes',
        'lead_id',
        'user_id',
        'application_no',
    ];

    protected $fillable = [
        'parent_stage_id',
        'user_id',
        'notes',
        'application_no',
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lead()
{
    return $this->belongsTo(Lead::class);
}
public function logTimeline($lead,$description, $activityType = null)
{
    $data = [
        'lead_id' =>$lead,
        'description' => $description,
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
public function campaign()
{
    return $this->belongsTo(Campaign::class, 'campaign_id');
}
public function source()
{
    return $this->belongsTo(Source::class, 'source_id');
}    public function project()
{
    return $this->belongsTo(Project::class, 'project_id');
}
public function parentStage()
{
    return $this->belongsTo(ParentStage::class, 'parent_stage_id');
}
}
