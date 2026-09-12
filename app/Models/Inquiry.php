<?php

namespace App\Models;

use Database\Factories\InquiryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    /** @use HasFactory<InquiryFactory> */
    use HasFactory;

    protected $fillable = ['branch_id', 'branch_name', 'name', 'contact', 'interest', 'status', 'internal_notes', 'ip_hash'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
