## Custom Events and Listeners ##

Last episode we hooked Eloquent Model to send mail.

The alternative option is using events. To create events use `make:event EventName`. To name EventName, generally use past tense.

`php artisan make:event ProjectCreated`

 After run this command Laravel adds `ProjectCreated.php` file in `app/Events` folder.
 This file has boilerplate code. We will not review `Broadcasting`, so we remove code related Broadcasting.
 
 I want to fire an event, so I need to inject `$project` instance to Listener.
 
 ```
 public function __construct($project)
 {
     $this->project = $project;
 }
 ```
 
 After I created a project, I can fire this event.

`event(new ProjectCreated($project));`

With this code I made an announcement and i can listen it anywhere in my project.

I can see that event in the Telescope. Telescope also show me the listeners that listen this event.

We should make a listener to listen events. To do this use `make:listener` command.

To name listener, use clean name and describe situation.

`php artisan make:listener SendProjectCreatedNotification --event=ProjectCreated`

With event parameter, Listener created with event. `ProjectCreated event` passed handle method.

```
public function handle(ProjectCreated $event)
    {
        \Mail::to($event->project->owner->email)->send(
            new ProjectCreatedMail($event->project)
        );
    }
```

Now we have one event to fire and one listener to listen that event.

At file there are two ProjectCreated s, so we need to name one of them. To name Mail class, we use as operator.  

```
use App\Events\ProjectCreated;
use App\Mail\ProjectCreated as ProjectCreatedMail;
```

When user created a new project in the controller, after create query, we fire custom event called `ProjectCreated`.

That event placed `Events/ProjectCreated.php`

Next, we registered a listener, that wants to handle `ProjectCreated event` in `handle` method.

Now, there is one-to-one register system between event and listener. But I haven't registered them to system.

In `Provider/EventServiceProvider.php` file, there is an array called `protected $listen`.

We can add our custom Events and related listener to this array.

```
ProjectCreated::class => [
    SendProjectCreatedNotification::class,
]
```

For every event, there are listeners. 

With this way, we can define many listeners for one event. Or we can use same listener for many events.

In telescope now i can see listener for this event. 

```
Listeners
App\Listeners\SendProjectCreatedNotification@handle  
```

Events an Listeners at first seem to complex. 

We used custom event but Eloquents has already fired its own event.

We can use `Eloquent event` in `project model`

```
protected $dispatchesEvents = [
    'created' => ProjectCreated::class
];
```

If we use this, we don't need to fire event in `ProjectController`.