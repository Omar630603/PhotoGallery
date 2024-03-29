<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $table = 'photos';
    protected $primaryKey = 'id_photo';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id_user',
        'title',
        'description',
        'img',
        'file_name',
        'extension'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
