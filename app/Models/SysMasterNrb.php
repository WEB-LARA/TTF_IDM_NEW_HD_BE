<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysMasterNrb extends Model
{
    use HasFactory;

    protected $table = 'sys_master_nrb';

    protected $primaryKey = 'ID';
    public $timestamps = false;
}
