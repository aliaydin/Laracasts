## Form Action Considerations ##

Detay sayfasında gösterilen `task` ler işaretlendiğinde DB ye `completed` olarak yazılmalıdır.

Her kayıt için bir Form eklendi. Her kayıt bir resorce olduğu için ayrı forma sahip olmalı. 

Formun metodu `POST` olacak mecburen ama aslında bir `Create` işlemi yapmıyoruz. 

Bir `update` işlemi olduğu için `@method('PATCH')` ekliyoruz. Peki form action ne olmalı?

`PATCH /projects/id/tasks/id` ya da `PATCH /tasks/id` 1. yöntem daha yaygın. 2. yöntem de kısa ve yapılabilir. 

Çünkü herbir task ın kendi id si var. Her iki versiyonda kullanımda. 

`bestPractice` URL olabildiğinde kısa olmalı. Yani 2. versiyon. Ama bu durumda `project_id` hidden gönderilmelidir.

Formu post için ayrıca bir submit düğmesine gerek yok. Form değiştiğinde submit olsun. 
Bunu js kodları eklemek yerine basitçe

`<input type="checkbox" name="completed" onchange="this.form.submit()"> {{ $task->description }}`

kodu iş görecektir. 

Checkbox a tıklandığında actiondaki url e gidilecek. 
Bu url için `web.php` ye bir rota tanımlamalıyız.

`Route::PATCH('/tasks/{task}', 'ProjectTasksController@update');`

Burada `TasksController` yerine `ProjectTasksController` kullandı. Bu controller ı tanımlamak gerekiyor.

`TasksController` da diyebilirdi ama aradaki ilişkiyi göstermesi açısından 2. yaklaşım daha doğru.

`php artisan make:controller ProjectTasksController`

Bu controller da Task modelini kullanmak için bind edilmeli. Önce use `App\Task;` ile Model eklenmeli. 
Daha sonra metoda parametre olarak verilecek.. `Route Model Binding`

`public function update(Task $task)`

Formu gönderip `dd` ile gelen `$task` ın içine baktığımızda attributes property sinde `task` nesnesini görürüz. 
Formdan gelen bilgiyi almak için `request('completed')` dememiz yeterli. 
Tabi checkbox sadece seçili olduğunda kendine ait değişkeni yollar.

Seçim kaldırıldığında bu değişken değeri `POST` datasından alınamaz.  

UnChecked durumu görmek için F12 ile Dev ekranına geç. 
Elements ten o checkbox ı içeren formu seç ve Console tabına geç.
 
`$0` dediğinde sana o DOM elementini verecektir. 
Yani burada Form. `$0.submit()` diyerek formu checkbox ı seçmeden post edebiliriz.
 
Bu durumda `request()->all()` ile baktığımızda seçili olmayan checkbox değişkeni gelmeyecektir.
Aslında bu senaryoyu `checked = true` `checkbox` ı yükleyip `change` ettiğimizde de görebiliriz. 

```
$task->update([
    'completed' => request()->has('completed')
]);
return back();
```

`has('competed')` true döner ve true ise o alana bool değeri yazarız. 
Eğer false ise 0 yani `unchecked` demektir.

`return back();` Formu `REFERER` a `redirect` eder. 

Formu gönderidiğimizde `massAssignment` hatası gelebilir. 
Düzeltmek için `task` modeline `protected $guarded = [];` ekledim.
Tasklerin durumlarını göstermek için 

`<input type="checkbox" name="completed" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>` 

şeklinde kodu güncellemek gerekir.

Bir `checkbox` a ait `check` durumunu gösterebilmek için `{{ $task->completed ? 'checked' : '' }}` kodu güzel.

Tamamlanmış görevlere css vermek için `layout.blade.php` ye gidip title etiketi içerisine

```
<style>
    .is-complete {
        text-decoration: line-through;
    }
</style>
```

kodunu ekledim.

Normalde sayfaya ait `css` kendi içerisinde durmalıdır. 
Ama `is-complete` farklı yerlerde de kullanılabilir diye `template` e eklendi.