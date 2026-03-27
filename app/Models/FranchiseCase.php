<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FranchiseCase extends Model
{
    protected $table = 'franchise_cases';

    protected $fillable = [
        'case_no',
        'applicant_name',
        'unit_id',
        'status',
        'filing_date',
        'expiry_date',
        'notes',
    ];

    protected $casts = [
        'filing_date' => 'date',
        'expiry_date' => 'date',
    ];
}
