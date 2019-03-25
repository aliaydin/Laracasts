<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    // massAssignment hatası için eklendi.
    // protected $fillable = ['title', 'description']; // whileList

    protected $guarded = [];

    // Mailables
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    // Your First Eloquent Relationships
    public function tasks() {
      return $this->hasMany(Task::class);
    }

    /*
    // Create New Project Tasks
    public function addTask($description)
    {

        // 2. yaklaşım. Project ve task arasındaki ilişkiyi kullanarak task a erişiyorum. Bu metot için tasks diyerek 2. bir sorgu çalışmıyor mu???
        $this->tasks()->create(compact('description'));
/*
        // 1. yaklaşım. Task modeli elimizde ve direkt onun create metodunu çağırıyoruz
        return Task::create([
            'project_id' => $this->id,
            'description' => $description
        ]);
*/
    /* } */

    // 3. yaklaşım. Metot doğrulamadan gelen diziyi parametre olarak alacak. Kod tek satır! Eğer ekstra sql çalışmıyorsa en güzeli bu.
    public function addTask($task) {
        $this->tasks()->create($task);
    }

}
