<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\CallRecordRequest;
class CallRecord extends Model
{
    protected $appends = ['is_superadmin', 'is_client', 'is_agency', 'is_channel_partner', 'is_channel_partner_manager',];

    public $table = 'call_records';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

// In your CallRecord model
protected $fillable = ['called_by', 'called_on', 'call_duration', 'call_start_time', 'status'];

}
