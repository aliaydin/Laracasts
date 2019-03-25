## Mailables ##

It is a side-effect to send mail in store method in ProjectsController.

`.env` file we choose `MAIL_DRIVER=log`, so actually we didn't send a mail.

To send a mail we must do a couple of things.

In `config/mail.php` we need to choose mail driver. Laravel supports some of them out of the box.

Laravel use `smtp` as default. `'driver' => env('MAIL_DRIVER', 'smtp')`, but we define `MAIL_DRIVER=log` in `.env` file.

Actually, you must send mail by test server, so you can test real life scenario.

In our send mail code, we use hardcoded mail address. `\Mail::to('mstfemk@gmail.com')`

But, in fact, we must use mail address who created that project.

Project has owner_id so we can make a relation use this field. Every project belongs to a owner.
We need to define this relation in Project model.

`return $this->belongsTo(User::class);`

If I use `$project->owner`, Laravel gives me the user who created this project.

We can get mail address by this code `\App\Project::latest()->first()->owner->email`

If you change a model after run Tinker, you must restart Tinker again to see changes.

```
\Mail::to($project->owner->email)->send(
    new ProjectCreated($project)
); 
```

With `new ProjectCreated($project)` code, we are creating Maillable instance. And give model data by `$project` parameter.

Mailable classes stored in `app\Mail` directory. This directory created after you call `php artisan make:mail` command.

`php artisan make:mail ProjectCreated --markdown="mail.project-created"`

With this code, you can tell Laravel to create a view to send mail.

At `project-created` view, There is a line `@component('mail::message')` It means use Laravel blade component.
This concepts comes from Vue components. Inspired by vue.

Sending mail takes a little time, so generally using queue to solve this problem. Last episode we will find out it.

Using `MAIL_DRIVER=log` is an option to see mail look like. But there is another way to do this to feel real word state.

`mailtrap` is a fake smtp testing server. To create an account you can use gmail account.

After singup you will see a mail in your inbox. That mail tells about mailtrap server configurations.

Use this setting to `.env` file. 

```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=c86dad8e49a41f
MAIL_PASSWORD=d65b424c3c8c24
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=mstfemk@gmail.com
MAIL_FROM_NAME="Mustafa Emek"
```

Sending a mail takes 2 seconds. So it is long time for real world. We must use queue.

Every property must set public, if you want to use them `markdown` view file.
And every data must inject to construct method in Mailables class. 



 

 