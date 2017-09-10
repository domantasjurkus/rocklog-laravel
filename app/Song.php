<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mpociot\Firebase\SyncsWithFirebase;

class Song extends Model
{

    use SyncsWithFirebase;

    protected $fillable = ['artist', 'song'];

    protected $visible = ['id', 'artist', 'song'];
}
