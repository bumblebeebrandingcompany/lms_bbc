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
        'followup_date',
        'followup_time',
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected $fillable = [
        'stage_id','lead_id',
        'created_by','notes'
    ];

    public function timeline()
    {
        return $this->hasMany(LeadTimeline::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
