<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasFactory;


    protected $table = 'datasets';


    protected $fillable = [
        'project_id',
        'serialNumber',
        'year',
        'dataset',
        'kindOfTraffic',
        'publicallyAvailable',
        'countRecords',
        'featuresCount',
        'doi',
        'downloadLinks',
        'abstract'
    ];


    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}