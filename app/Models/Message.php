<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'attachment',
        'attachment_name',
        'attachment_type',
        'attachment_size',
        'is_read',
        'is_blocked',
        'created_at',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function alerts()
    {
        return $this->hasMany(MessageAlert::class, 'message_id');
    }

    public function hasAttachment(): bool
    {
        return !empty($this->attachment);
    }

    public function isImageAttachment(): bool
    {
        if (!$this->hasAttachment()) {
            return false;
        }

        if ($this->attachment_type && str_starts_with($this->attachment_type, 'image/')) {
            return true;
        }

        $ext = strtolower(pathinfo($this->attachment_name ?? $this->attachment, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        if (!$this->hasAttachment()) {
            return null;
        }

        return Storage::url($this->attachment);
    }

    public function getFormattedAttachmentSizeAttribute(): string
    {
        if (!$this->attachment_size) {
            return '';
        }

        $bytes = $this->attachment_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' Mo';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' Ko';
        }
        return $bytes . ' o';
    }
}
