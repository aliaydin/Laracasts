## Cleaner Controllers and Mass Assignment Concerns ##

Eksik olan show metodu da diğer metotlarda olduğu gibi `findOrFail($id)` ile kayda erişim işlem yapılabilir.  

Index ten show a gitmesi için `<a href="/projects/{{ $project->id }}">` ile linki kullanılabilir.
 
Proje başka bir sunucuda farklı bir yerde çalışabilir diye linkleri `url` ya da `route` helperları kullanarak vermek gerekir.

`url('projects/' . $project->id) }}` Bu link proje nerede çalışırsa çalışsın uri ye gidecek. 

Biz neredeyse tüm metotlarda `$project = Project::findOrFail($id);` kodunu tekrar tekrar çalıştırdık.

Aslında Laravel bizim için `uri` de verilen `wildcard` için `find` işlemini zaten otomatik yapabiliyor.

Biz her metot için `($id)` demek yerine `(Project $project)` dediğimiz an, o id ye ait kayıt elimizde olur.

Bu tekniğe `Laravel Route Model Binding` deniyor. 

Laravel arka planda gidilen `resource` u bildiği için verilen parametreyi orada arıyor ve ilgili kaydı kullanıma hazır hale getiriyor.

Bunun için verilen parametreyi `id` olarak kullanıyor. İstenirse `id` yerine farklı bir alan da tanımlanabilir.

Project kaydını kullanan metotlarda

```
    ($id)
    {
        $project = Project::findOrFail($id);
``` 

satırları yerine 

```
    (Project $project)
``` 

satırı yeterli oluyor. Üstelik `findOrFail` gibi çalışıp olmayan kayıt için `404` otomatik veriliyor.

`show` `edit` `update` ve `destroy` metotları bu sayede düzenlenmiş oldu. Ve hepsi 1 ya da 2 satıra düştü.

`store` metodu yeni bir `$project` oluşturuyor ve onu doldurup `save()` metodunu çağırıyor. 

Bunu daha kısa yazmak için `static create` metodu kullanılabilir.

store metodundaki

```
$project = new Project();
$project->title = request('title');
$project->description = request('description');
$project->save();
```
kodu yerine

```
Project::create([
  'title' => request('title'),
  'description' => request('description')
]);
```

kodunu kullanabiliriz.

Fakat bunu çalıştırıdığımızda `MassAssignmentException` hatası geliyor. Bu hatanın nedeni Laravel in bizi korumasıdır.

Biz `Project` modeline henüz hiç kod yazmadık. 

Bize formdan ne gelirse gelsin `project resource` una ait olduğu için `Project` modelinin bir field i olarak değerlendiriliyor.

Bir kullanıcı gönderilen formu değiştirerek yeni alanlar ekleyebilir. Bu durumda alanlar uyuşursa onlar da DB ye eklenir.

Bu hata `create` ya da `update` metotları çağrıldığında geliyor. `save` kullanıldığında hata vermiyor. Çünkü alanlar tek tek ekleniyor.

Bu hatayı gidermek için hata mesajında denilenleri yapmak yöntemlerden 1 tanesidir.

```
MassAssignmentException
Add [title] to fillable property to allow mass assignment on [App\Project].
``` 

modelin içerisine `protected $fillable = ['title', 'description'];` eklemek gerekiyor. Artık sadece bu alanları dikkate alacak.

`$fillable` e eklenen alanlar `mass assignment` sırasında benim kabul ettiğim alanlar. Bu alanlar dışındaki herşeyi ignore ediyorum.

Bu sayede `DB` ye formda olmayan bir alana ait `update` gelirse korumuz oluruz.

Eğer `$fillable` ile alanları tek tek yazmak zor geliyorsa bunun tersi `$guarded` alanını kullanarak istemediğimiz alanları buraya yazabiliriz?

`$guarded` ise `blackList` çalışır ve burada tanımlı alanlar dışında ne gelirse gelsin kabul eder. Burayı boş bırakmak `[]` büyük sorumluluktur.

Bu gibi durumlarda hacklenmeye karşı kendi önlemlerimizi kendimiz almalıyız.

`['title' => request('title'), 'description' => request('description')]` ile `request(['title', 'description']);` kodu aynı çıktıyı verir.
 
Bu metot `string` arguman aldığında geriye `string value` dönerken `array` gönderdiğimizde, bize bu alanları ve alanlara ait değerleri `array key=value` olarak döner.

Yani request metoduna alanları `[]` arasında (dizi olarak) verirsek bize `key=>value` şeklinde diziyi döner.

Ayrıca `request()->all()` ile gelen tüm request bilgilerini dizi olarak alabiliriz.

Ama birisi bizim html formunu F12 ile değiştirip create formuna dahil etmediğimiz id gibi alanları eklerse 
bu alan da formla birlikte bize geleceği için onu da DB ye yollamış oluruz. 

Biz $guarded dizisini boş bırakırsak ve create içerisine de `request()->all()` gönderirsek forma eklenen alanlar direkt DB ye kaydedilecektir.

`request()->all()` içerisinde button dahil tüm form alanları gelir. Bu metodu `guarded` ile kullanmak doğru değildir. 

Formu F12 Edit As Html ile düzenleyip içerisine `<input type="hidden" name="id" value="10000">` bilgisini ekleyip gönderdiğimde 
hiçbir koruma olmadığı için id 10000 olarak DB ye kaydetti. Burada id değilde kullanıcı level tutan bir field çok rahat güncellenebilir.

Bu sorunu çözmek için 2 yaklaşım var.

1. __while list:__ Project modeline gidip fillable alanına `request()->all()` ile gelenlerden hangilerini kullanabileceğini tanımlayabilirsin.

`protected $fillable = ['title', 'description'];`

2. __black list:__ `$guarded = [];` bırakıp crete update gibi metotlara `request()->all()` ile gitmek yerine direkt alan isimlerini vermek.

__bestPractice__ 2. metot. Her create, update için alanları açıkça yazmak. Zaten bunun kısa yolu da var. `request([field1, field2])` şeklinde.

create düzenlendikten sonra update için de benzer işlem yapılıyor.

```
$project->title = request('title');
$project->description = request('description');

$project->save();
```

satırları yerine `Project::update(request(['title', 'description']));` satırı ekleniyor.

Artık ProjectController içerisindeki tüm metotlar 1 ya da 2 satıra düştü.

Eğer controller içerisinde metotlar şişmeye başlarsa `refactoring` yapmanın zamanı gelmiştir.