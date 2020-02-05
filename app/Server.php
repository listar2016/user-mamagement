<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    //
    protected $fillable = [
        'name', 'host_name', 'ip_address', 'user_name', 'password'
    ];
}
