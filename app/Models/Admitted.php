<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admitted extends Model
{
    use HasFactory;

    protected $appends = ['is_superadmin', 'is_agency', 'is_channel_partner', 'is_channel_partner_manager','is_presales','is_frontoffice'];

    public $table = 'admission';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',

        'follow_up_date',

    ];


    protected $fillable = [
        'parent_stage_id','lead_id','user_id','admission_no','follow_up_time',
        'notes'
    ];
    public function lead()
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }
    public function application()
    {
        return $this->belongsTo(ApplicationPurchased::class,'application_id');
    }
}
