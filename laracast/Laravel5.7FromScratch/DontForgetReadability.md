## Don't Forget Readability ##

We did readability lesson before. And today we will refactor ProjectsController.

At the index method, there is a line

`$projects = Project::where('owner_id', auth()->id())->get();`

It says give me the project where owner_id is equal authenticated user's id.

But we can just say give me the authenticated user's projects.

We can code as we say. auth()->user()->projects;

To do this we need relationship. In User model app/User.php we can add relation.

A user can has mady projects. So we call it hasMany relation.

```
public function projects()
{
    return $this->hasMany(Project::class, 'owner_id');
}
```

Eloquent always generate foreign key using modelName.primaryKey. So in this case it generates user.id.
And it generates sql sentence like this 
`select * from projects where projects.user_id = 1 and projects.user_id is not null`

But we used owner_id for projects table. 
hasMady method can take second argument named foreign key. So we need to pass owner_id our hasMany method.

In tinker we can get project data with this command `App\User::latest()->first()->projects`

We made validation many lessons ago. There is validation code in create method.

And we want to validate form as update. 

Using Form Request Class is another approach. But we keep it simple.

As a rule of thumb, keep it simple at start.

After validate if there is an error, redirect back. We used errors.blade create form before.
To show errors we add @include('errors') to edit page.

Using validation code different places is not a good practice. We must obey DRY prensible.
To do this we create a method called validateProject (just validate name is not good), and return validated data. 

```
public function validateProject()
{
    return request()->validate([
        'title' => ['required', 'min:3'],
        'description' => 'required'
    ]);
}
```

