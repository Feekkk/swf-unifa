<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommitteeVote extends Model
{
    protected $fillable = [
        'application_id',
        'committee_id',
        'vote',
        'amount_approved',
        'remarks',
    ];

    protected $casts = [
        'amount_approved' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the application that this vote belongs to.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the committee member who cast this vote.
     */
    public function committee(): BelongsTo
    {
        return $this->belongsTo(Committee::class);
    }
}
