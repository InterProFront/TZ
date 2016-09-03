<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {

    public function pages()
    {
        return $this->belongsTo('App\Page', 'project_id');
    }
}
