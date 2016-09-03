<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model {

    public function commit()
    {
        return $this->belongsTo('App\Commit', 'commit_id');
    }
}
