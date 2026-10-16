<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipInvitation extends Model
{
    protected $fillable = ['name', 'email', 'token', 'note', 'status', 'invited_by'];

    public function invitedBy()
    {
        return $this->belongsTo(Admin::class, 'invited_by');
    }
}
