## Don't Forget Readability ##

We did readability lesson before. And today we will refactor ProjectsController.

At the index method, there is a line

`$projects = Project::where('owner_id', auth()->id())->get();`

It says give me the project where owner_id is equal authenticated user's id.

But we can just say give me the authenticated user's projects.

We can code just as we say. `auth()->user()->projects;`

To do this, we need relationship. In User model `app/User.php` we can add relation.

A user can has many projects. So we call it `hasMany` relation.

```
public function projects()
{
    return $this->hasMany(Project::class, 'owner_id');
}
```

`Eloquent` always generate foreign key using `modelName.primaryKey`. So in this case it generates `user.id`

And it generates sql sentence like this
 
`select * from projects where projects.user_id = 1 and projects.user_id is not null`

But we used `owner_id` for projects table. 
hasMany method can take second argument named `foreign key`. So we need to pass owner_id our hasMany method.

In tinker we can get project data with this command `App\User::latest()->first()->projects`

We made validation many lessons ago. There is validation code in create method.

And we want to validate form as update. 

Using Form Request Class is another approach. But we keep it simple.

As a rule of thumb, keep it simple at start.

After validate if there is an error, redirect back. We used `errors.blade` file in create form before.
To show errors we add `@include('errors')` code to edit page.

Using validation code in different places is not a good practice. We must obey `DRY` prensible.
To do this we create a method called `validateProject` (just validate name is not good), and return validated data. 

```
public function validateProject()
{
    return request()->validate([
        'title' => ['required', 'min:3'],
        'description' => 'required'
    ]);
}
```

Everything comes back the basic readability. Ask yourself `"Does this read naturally?"`
If you have to write comment for your code, you must change your code.

Code can describe itself. You need to think `"how can i make it clear?"`

For example, get all project for the authed user. We must code it like we say.
This code is complex. It is really hard to understand.

`$projects = Project::where('owner_id', auth()->id())->get();`

But, `auth()->user()->projects;` this code is simple.

Even if your mother or wife can understand this code. Because it is very clear.

`Stick to the seven restful actions, not methods!` 