<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{

    use HasFactory;
    public $table = 'sitevisits';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',

    ];
    protected $casts = [
        'visit_date',
        'visit_time',
        'notes',
        'lead_id',
        'user_id',
        'application_no',
    ];
    protected $fillable = [
        'stage_id',
        'user_id',
        'notes',
        'application_no',
        'created_by'
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lead()
{
    return $this->belongsTo(Lead::class);
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
    return $this->belongsTo(ParentStage::class, 'stage_id');
}

public function timeline()
{
    return $this->hasMany(LeadTimeline::class, 'payload');
}
}
