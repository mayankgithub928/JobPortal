<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class job extends Model
{
    use HasFactory;

    function jobType(){
        return $this->belongsTo(JobType::class);
    }
    function category(){
       return $this->belongsTo(Category::class);
    }
}
