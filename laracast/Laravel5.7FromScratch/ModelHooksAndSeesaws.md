## Model Hooks and Seesaws ##

Last episode we send mail in store method. We consume sending email is a part of creating a project.
But it not proper way. It causes same complexity problems in your code.

We will examine a couple ways to solve this problem.

First option is Laravel Eloquent Model Hook. Laravel fire sometime an event. It is an announcement.
For example after a record created Laravel fires an event. To catch that event and to do someting go to Models.

In Project model, I create a method called boot. This method comes from Model, so I override it. (PHP doesnt have override key)
 
```
protected static function boot()
{
    parent::boot();

    static::created(function ($project) {
        // this code will only execute after a project created and store to DB.
        \Mail::to($project->owner->email)->send(
            new ProjectCreated($project)
        );
    });
}
```

static boot method comes from Model class. We override that method and to keep parent code we add parent::boot()
Then we call static create method. That method takes an closure. We can use that method to write our logic.

Now our code is more clean. We focus on the core action. store method just store the record. 
Any method might has core effect and side effect. In our example sending mail is a side effect.
We must avoid to code method has side effect. 

At first it is not easy. But with many years you can understand and code clean code.

If we want to send mail for updated and deleted projects, we just use static::updated and static:deleted method.