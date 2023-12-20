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
    ];

    protected $fillable = ['note_text','lead_id']; // Specify the fillable attributes
}
