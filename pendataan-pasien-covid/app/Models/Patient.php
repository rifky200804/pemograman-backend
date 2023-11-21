<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StatusPatient;


class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $fillable = ['name','phone','address','status_id','created_at','updated_at'];
    protected $primaryKey = 'id';

}
