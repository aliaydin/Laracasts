## User Notifications ##

If you need a help about artisan command you can use php artisan help commandName.
You can see usage, and argument list.

Sometimes mail is enough to notice user, but sometimes you need better than mail.
With notification you can send mail, log DB, call api, add a note user's notification panel, or send sms etc.

For example, we make a notification for user's susbcription renewal failed.

`php artisan make:notification SubscriptionRenewalFailed`

After running this comman Laravel create a file in app\Notifications\SubscriptionRenewalFailed.php

SubscriptionRenewalFailed class extends Notification class.

In this file the most important method is via. You can set notification channel with this method.

For every channel there is a method stating with `to`, for example for `mail` channel, there is `toMail` method.

Default via method use mail channel.

```
public function via($notifiable)
{
    return ['mail'];
} 
```

At User model, there is a line `use Notifiable;` This is a trait. 
And provides $user->notify(new SubscriptionRenewalFailed) code.
With this code we can notify user when the event fired.

To use notification, I added new route web.php called `home/notify`. And I notify code to notify method.
```
public function notify()
{
    $user = auth()->user();
    $user->notify(new SubscriptionRenewalFailed);
    return 'done';
}
 ```
 
 After I made a request, I see telescope my notification on notification tab.
 I also sent a mail without markdown file with this code
 
```
public function toMail($notifiable)
 {
     return (new MailMessage)
                 ->line('The introduction to the notification.')
                 ->action('Notification Action', url('/'))
                 ->line('Thank you for using our application!');
 }
 ```

line means paragraph, action is a link.

This code takes the class name and convert it an email subject.

To change subject add `->subject('New subject')` line.

If I just want to send mail, I can use Mailable. With notification I can also do another things.

If I add `database` field in array in via method,

`return ['mail', 'database'];`

With this code Laravel will try to insert your notification to notification table.

We need to create notification table. With artisan, we can do this easily.

`php artisan notification:table`

This code generate migration file, After this command we need to migrate our DB.

`php artisan migrate`

Now all notification logged this table. type field holds what kind of notification.
notification_id is generic (polymorfhic) field. With user it means user_id, with project it means project_id.
Generally we use it just user.

Now it will send an email and also insert notification record to notification db.

At the table type field holds notification class name, notifiable_type holds App\User class name, notifiable_type hold user id.
data field holds toArray method's result. To fill this field, change toArray method's array.

`return ['foo' => 'bar'];` and the result is `{"foo":"bar"}`

To send notification we used $user->notify. 
After notification we can use $user->notification to get all notificaiton related this user.  

```
php artisan tinker
$user = User::first()
$user->notifications
=> Illuminate\Notifications\DatabaseNotificationCollection 
$user->notifications()->first()->markasRead()
  read_at: "2019-04-03 08:05:43",
```

With tinker we can play our notification data.

When do I use a traditional email versus a notification?

That can be a little confused at first. The answer is What is taking of place?
 

