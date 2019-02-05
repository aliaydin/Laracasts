<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // massAssignment hatası için eklendi.
    // protected $fillable = ['title', 'description']; // whileList

    protected $guarded = [];

    public function tasks() {
      return $this->hasMany(Task::class);
    }

    public function addTask($task) {

        $this->tasks()->create($task);

        // $this->tasks()->create(compact('description'));
/*
        return Task::create([
            'project_id' => $this->id,
            'description' => $description
        ]);*/
    }

}
