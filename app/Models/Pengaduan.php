<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';
    protected $fillable = ['user_id', 'kategori', 'pengaduan', 'foto', 'status'];

    // setiap pengaduan milik satu user
    public function user() { return $this->belongsTo(User::class); }
}