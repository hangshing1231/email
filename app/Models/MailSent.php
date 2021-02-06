<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attachment;

class MailSent extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = [
        'to',
        'subject',
        'body',
    ];

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
