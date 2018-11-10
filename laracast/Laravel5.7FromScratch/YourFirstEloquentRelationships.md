## Your First Eloquent Relationships ##

Bu bölümde Eloquent kullanarak birbiriliyle ilişkili tablolar oluşturulacak. `projects` tablosu projeleri tutuyordu. Bazı projeler için görevler tanımlanabilir. Bunun için `tasks` tablosuna ihtiyaç var.

Bir tabloyu gidip `DB` de oluşturmak yerine artisan komutlarıyla `ORM` e uygun bir biçimde hem kodu hem de modeli oluşturabiliriz. Ayrıca Task için `Factory?` de oluşturacağız.

`php artisan help make:model` ile öncelikle kullanılabilir komutları inceliyoruz. Tüm komutlar oturuncaya kadar bu şekilde çalışılmalı.

`-a` parametresi ile çağırsak bizim için `migration, Model Factory, resource Controller` oluşturuyor. Bizim controller a ihtiyacımız olmayabilir. Bu yüzden `migration` için `-m` ve `factory` için `-f` parametrelerini kullanacağım.

`php artisan make:model Task -m -f` komutunu çalıştırdığımızda bizim için  `app\Task.php` modelini, `\database\migrations\create_tasks_table.php` migration unu ve son olarak `\database\factory\TaskFactory.php` factory sini oluşturdu.

İlk olarak migrationu düzenleyerek `tasks` tablosunun sahip olduğu alanları eklemeliyiz. `up` metoduna aşağıdaki alanları ekledim.

```
$table->unsignedInteger('project_id'); // integer deseydik negatif sayıda eklenebilirdi. project tablosuyla bağlantı için
$table->string('description');
$table->boolean('completed')->default(false); // Tırnak yok, boolean değer veriyorsun.
```

`2018_11_09_072121_create_tasks_table.php` dosyasına yazarken izin sorunu yaşadım. chmod 777 ile geçici olarak çözdüm.

Yeni tablo için migrate dosyası hazırlandığına göre artık DB yi migrate edebilirim.
`php artisan migrate`

Yeni oluşan `tasks` tablosu ile `projects` tablosunu birbirine bağlamak için. Master olan `Project` modeline gidip `tasks` isminde bir metot ekliyorum. İlişki için çok fazla metot var. Ama biz şimdilik 1 tanesini kullanacağız. Diğerleri dokümanda.

`return $this->hasMany(Task::class);` kodu ile project referansını tutan nesnenin `tasks()` metodunu çağırdığımızda ona ait task ların gelmesini sağlayabiliriz. Burada bir projede birden fazla tasks olabileceği için `hasMany` ilişkisi üzerinden bağlantı kuruldu.

Yapılan düzenlemeleri kodda denemek yerine tinker ile kontrol edebiliriz.

`php artisan tinker` ile komut satırını açtıktan sonra,

`App:Project::First()` ile ilk projeyi çekiyorum.

`App:Project::First()->tasks;` ile de bu projeye bağlı task ları çekiyorum. Burada tasks ile eriştiğimiz ve property gibi duran şey aslında bizim az önce eklediğimiz metot.

DB ye gidip `tasks` tablosuna ilk projeye bağlı yeni bir kayıt eklediğimde `App:Project::First()->tasks;` komutuyla bu kayıta eriştim.
```
@foreach($project->tasks as $task)
    <li>{{ $task->description }} </li>
@endforeach
```
Kodu ile bir proje detayına bakıldığında ona ait task ları da görüntüleyebiliyorum. Bir projeye ait task var mı diye kontrol etmek için `$project->tasks->count()` kullanabilirsin.

Eğer `hasMany` değil de tasks açısından bakıp `belongsTo` ilişkisi kurmak istersen bu sefer Task modeline projects() isimli bir metot eklemelisin. Bu 2 ilişki türü pek çok proje için yeterli olacaktır.

```
public function project() {
    return $this->belongsTo(Project::class);
}
```

Tinker da baktığımda 2 tablo birbirine bağlı görünüyor. Task üzerinden projeye erişebiliyorum.

```
>>> App\Task::first()
=> App\Task {#2910
     id: 1,
     project_id: 1,
     description: "1. projenin taskı",
     completed: 1,
     created_at: "2018-11-03 12:28:29",
     updated_at: "2018-11-09 11:42:56",
   }
>>> App\Task::first()->project;
=> App\Project {#2908
     id: 1,
     title: "My first Project UPDAte",
     description: "My first desc UPDAte",
     created_at: "2018-11-03 12:28:29",
     updated_at: "2018-11-05 11:08:05",
   }
```

Biz `App\Task::first()` dediğimizde 1 query çalıştırıyoruz. `App\Task::first()->project` diyerek 2. bir query çalışıyor. Tani biz tanımlama yaptık diye kesinlikle joinler otomatik çalışmıyor. Sadece biz kullanmak istediğimiz durumda join yapılıyor.
