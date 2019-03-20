## Simpler Debugging With Laravel Telescope ##

During a project, an application doing uncountless things like send mail, queyr database, logging etc.

Sometimes it is very difficult to track your application. 

For example if we want to check query result we must go to sql client and look at the tables.

It takes much time and Laravel gives us a professional tool for kind of these things.

To install Laravel Telescope

`composer require laravel/telescope --dev`

We choose --dev installation because generally debug tools use in local environment.

After we installed the package, we must run php artisan telescope:install

With this command telescope create migration files, add Service Provider etc.

To see change go to config folder and you will see telescope.php file. You can modify watchers here.
In `app/Providers/TelescopeServiceProvider.php` file you can see Telescope install for local by default.
You can customize what user can debug. To do this add mail address in gate() method.
That email address must be in users table.

```
Gate::define('viewTelescope', function ($user) {
    return in_array($user->email, [
        'mstfemk@gmail.com'
    ]);
}); 
```

And to migrate migration files, php artisan migrate

If you get error like "Syntax error or access violation: 1071 Specified key was too long; max key length is 1000 bytes"
read [https://github.com/mstfEmk/laravel-notes/blob/master/migrate1071.md](this) note.

After migration we have 3 new tables.

To use Telescope we should! define virtual host. Read [ApacheVirtualHostLink](this) note

To use it we call `{project.addres}/telescope` 

On top right there is a stop button. If you want to stop logging you can use it.

When you make a request, this page says `Load New Entries` So when you click you get last logs.

To get details click the button on the right. 

I got error when i logged. Because `Telescope 2.0` want to use `Laravel 5.8` 

`Telescope 2.0` is compatible with `Laravel 5.8` not `5.7.*` So I need to `upgrade` Laravel.

To do this update `laravel/framework` dependency to `5.8.*` in `composer.json` file. Then `run composer update` command.

Requests tab in Telescope shows us Request and Response. And It also shows Queries and their Duration.

If you want to see all queries go to queries tab.

Requests page very useful because we can check it after every request and see what was happening.

If we want to return $project variable at index method in ProjectsController, we get json.

`[{"id":1,"owner_id":1,"title":"Deneme","description":"Denemeye ait proje","created_at":"2019-03-20 12:16:10","updated_at":"2019-03-20 12:16:10"}]`

But if we use `dump` command. Behind the scene Laravel uses a Symfony tool called var dumper. 
At the same time Telescope hooks this command and save dump for as. 
So after dump command we can see log only Telescope Dump tab. It is very clean.

We can also use Telescope to watch cache. For example we take some statistics from DB.
And we want to cache it because of performance issues.

```
cache()->rememberForever('stats', function() {
   return ['lessons' => 130, 'hours' => 1300, 'series' => 7];
});
```

We we load page. Telescope Cache tab says missed and set. 
I means when you make a request that page, if there is no cache it missed and if it missed it caches.
If you call that page again this time it says hit. 

We can catch this cache anywhere. For example we can use php tinker to get cached data.

```
php artisan tinker
cache()->get('stats')
=> [
     "lessons" => 130,
     "hours" => 1300,
     "series" => 7,
   ]

```

You can get and dump it in your code

```
$stats = cache()->get('stats');
dump($stats);
```

We can also log mails. For example we will send a mail after create a project.
Laravel helps us with make:mail command. 

`php artisan make:mail ProjectCreated --markdown="mail.project-created"`

After this comman it created app/Mail/ProjectCreated.php file. That class inherits Mailable class.

`class ProjectCreated extends Mailable`

And it uses template that we give it markdown parameter in build method.

```
public function build()
{
    return $this->markdown('mail.project-created');
}
```

With markdown parameter we create a template in `resources/views/mail/project-created.blade.php`

To send this mail we add code below. And we must sure add `use App\Mail\ProjectCreated;` line. 

```
\Mail::to('mstfemk@gmail.com')->send(
    new ProjectCreated($project)
);
```

With this code, We say send a mail `'mstfemk@gmail.com'` and use `ProjectCreated` mailable class.
And that class uses `mail.project-created.blade.php` file for schema.

So we can customize blade file. 

To send mail, we must configure .env file. But we just want to simulate it and if we change 
`MAIL_DRIVER=smtp` to `MAIL_DRIVER=log`, we never get error. And mail will send our log file `(storage/logs/laravel-{today})`

After this configuration we can see our mail in bottom of the log file. And ofcourse mail tab in Telescope.

To use $project data in our mail class, we should add it as a public field.
We gave $project data when we send mail. So ProjectCreate class took this data on consruct method.

```
public function __construct($project)
{
    $this->project = $project;
}
```

Our build method we didn't pass $project field but i can use it because of this field is marked public.

And when we create another project we get project information. 

  