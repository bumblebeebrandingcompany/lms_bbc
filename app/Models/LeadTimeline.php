<?php

namespace App\Models;

// app/Models/LeadTimeline.php

use Illuminate\Database\Eloquent\Model;

class LeadTimeline extends Model
{


    protected $table = 'leadtimeline';

    protected $fillable = ['lead_id', 'description','activity_type','payload'];



    public function lead()
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }

    public function note()
    {
        return $this->belongsTo(Note::class,'payload');
    }

    public function sitevisit()
    {
        return $this->belongsTo(SiteVisit::class,'payload');
    }

    public function followup()
    {
        return $this->belongsTo(Followup::class,'payload');
    }

    public function callrecord()
    {
        return $this->belongsTo(CallRecord::class,'payload');
    }

}
