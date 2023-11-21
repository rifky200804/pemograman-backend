<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient;


class StatusPatient extends Model
{
    use HasFactory;
    protected $table = 'status_patients';
    protected $fillable = ['status','in_date_at','out_date_at','created_at','updated_at'];
    protected $primaryKey = 'id';

}
