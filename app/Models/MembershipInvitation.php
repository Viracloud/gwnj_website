<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipInvitation extends Model
{
    /** An invitation link works for this many days after it was sent. */
    public const VALID_DAYS = 30;

    protected $fillable = ['name', 'email', 'token', 'note', 'status', 'invited_by'];

    /** Pending invitations whose link has not expired yet. */
    public function scopeUsable($query)
    {
        return $query->where('status', 'pending')->where('created_at', '>=', now()->subDays(self::VALID_DAYS));
    }

    public function invitedBy()
    {
        return $this->belongsTo(Admin::class, 'invited_by');
    }
}
