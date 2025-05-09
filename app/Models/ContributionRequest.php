<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContributionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'project_id',
        'serialNumber',
        'year',
        'dataset',
        'kindOfTraffic',
        'publicallyAvailable',
        'countRecords',
        'featuresCount',
        'cite',
        'citations',
        'attackType',
        'downloadLinks',
        'abstract',
        'status',
    ];


    // Relationship to Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
