<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'content',
        'status',
        'highlight',
        'user_id',
    ];

    protected $casts = [
        'highlight' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->status = 'draft';
            $query->highlight = 1;
            $query->user_id = auth()->user()->id;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeWithFilters($query){

    }
}
