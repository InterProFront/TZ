<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Commit extends Model {

    public function page()
    {
        return $this->belongsTo('App\Page', 'page_id');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer', 'commit_id');
    }
}
