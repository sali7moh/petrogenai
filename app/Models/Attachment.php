<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'filename',
        'original_filename',
        'mime_type',
        'size',
        'path',
        'extracted_text',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function getFullPath()
    {
        return storage_path('app/' . $this->path);
    }

    public function getSizeInKB()
    {
        return round($this->size / 1024, 2);
    }
}
