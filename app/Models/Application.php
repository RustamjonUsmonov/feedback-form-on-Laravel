<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Application extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'applications';
    protected $fillable = ['fullname', 'topic', 'message', 'email', 'read','user_id'];
    protected $casts = [
        'read' => 'boolean'
    ];
}
