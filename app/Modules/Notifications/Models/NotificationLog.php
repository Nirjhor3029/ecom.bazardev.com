<?php

namespace App\Modules\Notifications\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    public $timestamps = true;

    protected $fillable = ['channel', 'to', 'template', 'status', 'provider_ref', 'error'];
}
