<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationPurchased extends Model
{
    use HasFactory;

    protected $appends = ['is_superadmin', 'is_agency', 'is_channel_partner', 'is_channel_partner_manager','is_presales','is_frontoffice'];

    public $table = 'application_purchased';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'follow_up_date',

    ];
    protected $fillable = ['lead_id', 'who_assigned', 'for_whom', 'application_no', 'follow_up_date', 'notes', 'follow_up_time', 'parent_stage_id'];

    public function lead()
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }
    public function application()
    {
        return $this->hasMany(ApplicationPurchased::class,'application_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class,'for_whom');
    }
}
