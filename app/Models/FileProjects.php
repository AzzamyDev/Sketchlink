<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileProjects extends Model
{
    use HasFactory;
    protected $table = 'path_projects';
    
    protected $fillable = ['name', 'user_id', 'path'];

}
