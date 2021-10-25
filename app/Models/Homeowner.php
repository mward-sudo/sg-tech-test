<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homeowner extends Model
{
    protected $fillable = ['title', 'first_name', 'initial', 'last_name', 'upload_file'];

    use HasFactory;
}
