<?php
namespace App\Models;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public $table = 'notes';

    protected $dates = [
        'created_at',
        'note_text'
    ];

    protected $fillable = ['lead_id','parent_stage_id']; // Specify the fillable attributes


    public function rules()
    {
        return [

            'note_text' => [
                'string',
                'nullable'

            ],
        ];
    }


    public function timeline()
{
    return $this->hasMany(LeadTimeline::class);
}
}
