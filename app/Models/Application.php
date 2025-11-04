<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'subcategory',
        'amount_applied',
        'application_data',
        'bank_name',
        'bank_account_number',
        'status',
        'admin_notes',
        'amount_approved',
        'reviewed_at',
        'reviewed_by',
        'verified_by',
        'committee_remarks',
    ];

    protected $casts = [
        'application_data' => 'array',
        'amount_applied' => 'decimal:2',
        'amount_approved' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the committee member who reviewed the application.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Committee::class, 'reviewed_by');
    }

    /**
     * Get the admin who verified the application.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    /**
     * Get all documents for this application.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    /**
     * Get the total amount for the application.
     */
    public function getTotalAmountAttribute(): float
    {
        if ($this->amount_applied !== null) {
            return (float) $this->amount_applied;
        }

        // Fixed amounts based on subcategory
        return match($this->subcategory) {
            'student' => 500.00,
            'parent' => 200.00,
            'sibling' => 100.00,
            default => 0.00,
        };
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
