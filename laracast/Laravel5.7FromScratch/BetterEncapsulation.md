## Better Encapsulation ##

__Encapsulation:__ Hide internal state and values inside a class.

Biz `ProjectTasksController` a ait `store` metodunda `$project->addTask($attributes);` 
kodu ile `Project` modeline ait `addTask` metodunu çağırdık. 

Isteseydik `Project` modelindeki `addTask` metoduna ait tek satırlık

`$this->tasks()->create($task);`

kodunu `ProjectTasksController` ait `store` metodunda kullanabilirdik. 

Fakat bunu aşağıdaki sebeplerden dolayı yapmamalıyız.

Project modelindeki `addTask` metodu kolayca anlaşılabilirken, 
`ProjectTasksController` ı içerisindeki `store` metodunda bulanabilecek 

`$project->tasks->create(request()->all())` 

kodu daha karmaşık kalır. 

Buradaki karmaşıklık`project` ile `tasks` ilişkisini bilip, `eloquent` in `update` metodunu çağır ve bu alanları db ye aktar.

Aslında Project modeli kendine veri eklemeye kendi yapmalıdır. 
Biz sadece onun metodunu çağırmalıyız. `tell don't ask`. 
Ayrıca bir soyutlama da var. Controller project modeline ait store metodunu ve project tablosunu alanlarını bilmemelidir.

Burada kodu gerçekten onu içermesi gereken kısma havale edip, dışarıdan sadece onu çağırmalıyız.
Bu sayede hem kod kapsullenmiş hem de soyutlanmış oldu.

`ProjectTasksController` a ait `update` metodunda biz `$task->update` diyerek direkt bir db işi yapıyoruz. 
Bizim update kodumuz:

```
$task->update([
    'completed' => request()->has('completed')
]);
```

Burada `$task->update` ile `Task` modeline ait metodu çağırıyoruz. 
Ayrıca DB deki alan hakkında da bilgi veriyoruz. Ayrıca Task modeli dururken onu işini controller da biz yapıyoruz.

Bir de Controller `task` ın tamamlanması işini söylese yeter. Implementasyon controller ı ilgilendirmemeli.

`store` içerisine `$task->complete()` kodunu ekleyerek implementasyonu ait olduğu Task modelinde yapılmasını sağlayabiliriz.

`update` metodunun içerisini `$task->complete(request()->has('completed'));` şeklinde düzenledim 
ve `Task` içindeki `complete` metodunda DB ye erişme işini yapdım. `$this->update(compact('completed'));`
Artık controller sadece complete metoduna gelen parametreyi veriyor.

Şu an kod çok daha iyi kapsullenmiş oldu. 

Tek sorun complete metodunu false ile çağırdığımızda incomplete yapması,bu biraz kafa karıştırıyor.

`complete metoduna true false vermek kafa karıştırıyor. incomplete yazıp onu sürekli false, diğerini true yapmak daha doğru.`
 
Bu durumda Task içine incomplete isminde bir metot tanımlayıp controler daki update içerisinde 
if ile ya da ternary kullanarak bu 2 metot tan hangisinin çağrılacağına karar vermek.

```
public function incomplete() {
    $this->complete(false);
}
```

Artık controller daki `store` metodunda

`request()->has('completed') ? $task->complete() : $task->incomplete();`

kodu gelen parametreye göre ilgili metodu çağıracak. Dinamik metot çağırmak için kodu aşağıdaki gibi de kullanabiliriz.

```
$method = request()->has('completed') ? 'complete' : 'incomplete';
$task->$method();
```

Dinamik metot çağırmak kodun okunabilirliğini düşürüyor.