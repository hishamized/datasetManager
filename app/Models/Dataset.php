<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasFactory;


    protected $table = 'datasets';

    protected $casts = [
        'custom_attributes' => 'array',
    ];

    protected $fillable = [
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
        'custom_attributes'
    ];


    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
