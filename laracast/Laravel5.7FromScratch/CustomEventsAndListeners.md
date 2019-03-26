## Custom Events and Listeners ##

Last episode we hooked Eloquent Model to send mail.

The alternative option is using events. To create events use make:event EventName. EventName is usually past tense.

`php artisan make:event ProjectCreated`

 After run this command Laravel adds ProjectCreated.php file in app/Events folder.
 This file has boilerplate code. We will not review Broadcasting, so we remove code related Broadcasting.
 
 I want to fire an event, so I need to inject $project instance to Listener.
 
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

We should make a listener to listen events. To do this use make:listener command.
Ta name listener, use clean name and describe situation.

`php artisan make:listener SendProjectCreatedNotification --event=ProjectCreated`

With event parameter, Listener created with event. ProjectCreated event passed handle method.

```
public function handle(ProjectCreated $event)
{
    //
}
```

!!! Option 2 will study again ... !!!

Event and Listener concept are to diffucult to understand at first. 
But it is easiest implementation for events and listeners.



 
 