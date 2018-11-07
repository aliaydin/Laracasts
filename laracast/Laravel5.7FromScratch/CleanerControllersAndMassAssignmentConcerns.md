## Cleaner Controllers and Mass Assignment Concerns ##

Bu ders refactor yapılacak. Aynı çıktıyı kodları daha düzenli ve kısa yazarak elde etmeye çalışacağız.

İlk olarak eksik olan show metodunu da ProjectsController a ekledim.

Index ten burada `<a href="/projects/{{ $project->id }}">` ile link verdim. Views altına `show.blade.php` yi ekledim.

Biz neredeyse tüm metotlarda `$project = Project::findOrFail($id);` kodunu tekrar tekrar çalıştırdık.

Aslında Laravel bizim için `uri` de verilen `wildcard` için `find` işlemini zaten otomatik yapabiliyor.

Biz her metot için `($id)` demek yerine `(Project $project)` dediğimiz an, o id ye ait kayıt elimizde olur.

Bu tekniğe `Laravel Route Model Binding` deniyor. Detayı manual de var.

`show` `edit` `update` ve `destroy` metotları bu sayede düzenlenmiş oldu.

`store` metodu yeni bir `$project` oluşturuyor ve onu doldurup `save()` metodunu çağırıyor. Bunu daha kısa yazmak için `static create` metodu kullanılabilir.

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

Biz `Project` modelini tanımlamadık. Bize formdan ne gelirse gelsin `project resource` una ait olduğu için `Project` modelinin bir field i olarak değerlendiriliyor.

Bu hata `create` ya da `update` metotları çağrıldığında geliyor. Bu hatayı gidermek için modelin içerisine `protected $fillable = ['title', 'description'];` eklemek gerekiyor. Artık sadece bu alanları dikkate alacak.

Eğer `$fillable` ile alanları tek tek yazmak zor geliyorsa bunun tersi `$guarded` alanını kullanarak istemediğimiz alanları buraya yazabiliriz? ($guarded tam böyle mi çalışıyor?)

Bu sayede `DB` ye formda olmayan bir alana ait `update` gelirse korumuz oluruz. `$guarded` alanını boş bırakmak tüm sorumluluğu üzerimize almak demektir. Bu gibi durumlarda hacklenmeye karşı kendi önlemlerimizi kendimiz almalıyız.

Yukarıda kullandığımız reuest metodu `request(['title', 'description']);` şeklinde de çalışabilir. Bu metot string arguman aldığında geriye string dönerken array gönderdiğimizde, bize bu alanları ve alanlara ait değerleri array olarak döner. Ayrıca `request()->all()` ile gelen tüm request bilgilerini dizi olarak alabiliriz.

`request()->all()` bizi büyük kolaylıklar sağlar fakat bunun yanında birisi bizim html formunu F12 ile değiştirip create formuna dahil etmediğimiz id gibi alanları eklerse bu alan da formla birlikte bize geleceği için onu da DB ye yollamış oluruz. Biz $guarded dizisini boş bırakırsak ve create içerisine de `request()->all()` gönderirsek forma eklenen alanlar direkt DB ye kaydedilecektir.

`request()->all()` içerisinde button da geliyordu onu engellemek için name alanını kaldırdım. Formu F12 Edit As Html ile düzenleyip içerisine `<input type="hidden" name="id" value="10000">` bilgisini ekleyip gönderdiğimde hiçbir koruma olmadığı için id 10000 olarak DB ye kaydetti. Burada id değilde kullanıcı level tutan bir field çok rahat güncellenebilir.

Bu sorunu çözmek için 2 yaklaşım var.

1. __while list:__ Project modeline gidip fillable alanına `request()->all()` ile gelenlerden hangilerini kullanabileceğini tanımlayabilirsin.

`protected $fillable = ['title', 'description'];`

2. __black list:__ `$guarded = [];` bırakıp crete update gibi metotlara `request()->all()` ile gitmek yerine direkt alan isimlerini vermek.

__bestPractice__ 2. metot. Her create, update için alanları açıkça yazmak. Zaten bunun kısa yolu da var. `request([field1, field2])` şeklinde.

create düzenlendikten sonra update için de aynı satır ekleniyor. `Project::update(request(['title', 'description']));`

Artık ProjectController içerisindeki tüm metotlar 1 ya da 2 satıra düştü.
