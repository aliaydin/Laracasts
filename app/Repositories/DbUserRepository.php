<?php
namespace App\Repositories;

class DbUserRepository implements UserRepository {
    public function create($attributes) {
        // User::create();
        dd("creating the user");
    }
}
