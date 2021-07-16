<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Review;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function file()
    {
        return $this->hasOne(Files::class, 'project_id');
    }
    public function icon()
    {
        return $this->hasOne(Icon::class, 'project_id');
    }
    public function ss()
    {
        return $this->hasMany(Screenshot::class, 'project_id');
    }


    public function review()
    {
        // $rev = $this->hasMany(Review::class);
        // return count($rev->get());

        // $review = $this->hasMany(Review::class)
        // ->selectRaw('reviews.project_id,SUM(reviews.stars) as stars' )
        // ->groupBy('reviews.project_id');

        return $this->hasMany(Review::class, 'project_id', 'id');

    }



}
