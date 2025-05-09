<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'start_date', 'end_date', 'students', 'guide_name',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function datasets()
    {
        return $this->hasMany(Dataset::class);
    }
    public function contributionRequests()
    {
        return $this->hasMany(ContributionRequest::class);
    }
}
