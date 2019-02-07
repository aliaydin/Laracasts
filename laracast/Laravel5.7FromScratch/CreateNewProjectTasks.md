## Create New Project Tasks ##

Projeye ait task ları artık görüntüleyebilir ve completed olduğunda güncelleyebiliyoruz. 
Bu bölümde yeni task ekleme ekranı yazılacak.

`show.blade` e yeni task girişi için gerekli form eklenmeli. Bu form o projeye ait yeni bir task eklemelidir. 
Formu post edileceği yer için kısa versiyon bu şekilde olabilir. REST e uygun olan yaklaşım da budur.

`Route::post('/tasks', 'ProjectTasksController@store');`

Kısa versiyon kullandığımızda hangi projeye ait task olduğunu da belirtmemiz için proje_id sini de form ile yollamalıyız.

Ya da uzun versiyon kullanılabilir. Uzun versiyonda projeId si url de gidiyor.
Bir önceki bölümde kısa versiyon kullanılmıştı. Burada deneme amaçlı uzun versiyon kullanılacak.

`Route::post('/projects/{projects}/tasks', 'ProjectTasksController@store');`

ProjectTasksController da store metoduna bu task ı eklemek için gerekli kodu yazacağız.
Bu kodda `Project` modelini metoda parametre olarak veriyoruz. Sonuçta bu proje bilgisi url den geliyor.

```
public function store(Project $project) {
    Task::create([
        'project_id' => $project->id,
        'description' => request('description')
    ]);
    return back();
}
```

Bu yöntem güzel bir yöntem ama daha temiz kod yazmak için başka bir yöntem daha var.
Biz aslında `projeye` bir `task` ekliyoruz. `Task` kendine bir `task` eklemek yerine `proje addTask` ile `task` ı ekleyebilir. 

Yukarıdaki yöntemde kodu inceleyen adam `Task` ile `Project` arasında `project_id` üzerinden bir bağlantı olduğunu 
koda bakınca anlamayabilir, gidip DB de kontrol yapabilir. Bunun yerine daha kolay bir yaklaşım var. (Tavsiye edilen yaklaşım)

Bu yaklaşımda Project modeline `addTask` isminde bir metot ekleniyor ve `create` metodunun içeriği 
bu modelin içerisine ekleniyor.

`store` metodu aşağıdaki gibi sadeleşirken buradaki kod `ProjectTasksController.php` altına `addTask` 
metodu açılarak oraya ekleniyor.

```
$project->addTask(request('description'));
return back();

public function addTask($description) {
    return Task::create([
        'project_id' => $this->id,
        'description' => $description
    ]);
}
```

Aslında `Project` modelinde Eloquent ile `Task` bağlantısı olduğundan dolayı `addTask` metodundaki

```
return Task::create([
    'project_id' => $this->id,
    'description' => $description
]);
```

kodunu kaldırıp yerine

`$this->tasks->create(compact('description'));`

kodunu ekleyerek aynı sonuca ulaşılabilir. Bu kodda `$this` url de id si verilen Project nesnesini temsil ediyor. 
Biz onun altına eklediğimiz tasks ile ona ait task ları şekebiliyorduk.
Buradaki yaklaşımda `$this->tasks` dediğimiz an 2. bir sorgu çalışıyor mu?
Son yaklaşımın bize faydası `id` göndermeye de gerek kalmaması. 

`create` ile de onun altındaki tasklara 1 tane daha yeni task ekliyoruz. 
Bunu eklerken de sadece description alanını veriyoruz. 
Burada girilmesi gereken `project_id` alanını zaten ilişkiden dolayı biliyor.

Formda doğrulama işlemi yapılmıyor. `FE` ve `BE` de doğrulama yapmak gerekiyor.

BackEnd için `ProjectTasksController.store` a

```
$attributes = request()->validate(['description' => 'required']);
$project->addTask($attributes);
```

kodu ile önce validate metoduna dizi olarak veridiğim alanları valide edip sonucu dizi olarak alıyorum.
`BE` de doğrulama her zaman `Controller` da yapılmalıdır. Modele kadar gitmemelidir.

`addTask` metodu da değiştiği için (önceden `$description` alıyordu) onu da bu dizi alacak hale getirdim.

```
public function addTask($task) {
    $this->tasks()->create($task);
}
```

Bu diziyi de `addTask` metoduna direkt verebiliyorum. 
Kodu refactor etmeden önce description alanını dizi olarak göndermek için `compact` metodunu kullanıyordum.

Doğrulama sırasında oluşan hataları göstermek için `create_blade.php` deki kodları alıp aynen kullanabiliyoruz. 
`DRY` gereği aynı kodu `copy&paste` yapmak yerine reusable hale getirmeli ve her yerden tek bu koda erişmeliyiz.

Bunun için partial include yapabiliriz. `resource/views` klasörüne `errors.blade.php` isminde bir view 
oluşturup kodu bunun içerisine yapıştırdım. Bu kodu kullanacağım yere `@include('errors')` demem yeterli.
