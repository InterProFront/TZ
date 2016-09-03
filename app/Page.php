<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model {

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_id');
    }

    public function commits()
    {
        return $this->hasMany('App\Commit', 'page_id');
    }
}
