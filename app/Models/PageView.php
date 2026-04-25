<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'url', 'route_name', 'ip', 'user_agent',
        'user_id', 'viewed_at', 'referer',
        'device_type', 'browser', 'os',
        'country', 'is_new_visitor', 'session_duration',
    ];

    protected $casts = [
        'viewed_at'      => 'date',
        'is_new_visitor' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
