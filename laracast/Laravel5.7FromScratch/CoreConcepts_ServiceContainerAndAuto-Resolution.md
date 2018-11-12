## Core Concepts: Service Container and Auto-Resolution ##

__Laravel Service Container:__ Laravel FW nin en temel kavramlarından birisidir.

ProjectsController daki `show` metodunda `public function show(Project $project)` koduyla Project modelini `Route Model Binding` kullanarak alabiliyoruz. Mesela show a parametre olarak `FileSystem` sınıfını vermek isteyeyim.

Bunun için öncelikle `use Illuminate\FileSystem\FileSystem;` ile namespace i ekledim. Daha sonra metodu parametre olarak gönderip `dd` ile ekrana bastırdım.

```
public function show(FileSystem $file) {
    dd($file);
```
Ekrana gelen çıktı. `Filesystem {#104}`

Yani metoda parametre olarak verdiğimiz tipteki nesneyi Laravel bizim için otomatik oluşturup veriyor.
Laravel bunu yapabilmek için arka planda 2 teknoloji kullanıyor.
1. __AutoResolving:__ Php Reflection kullanarak verilen sınıf isminde bir nesne oluşturuyor.
2. __Service Provider:__ key-value ikilisi şeklinde servisler bir yerde duruyor. Laravel istenen sınıf orada ise onu resolve ederek bize geri veriyor.

Denemeler yapmak için en güzel yer web.php dir. Sonuçta tüm istekler buradan geçer ve isteği keserek kendi kodlarımızı ekleyebiliriz.

Service Provider a erişmek için `app()` metodunu kullanabiliriz. Bu metot aslında resolve() metodunun aliasıdır. Aynı işlemi `web.php` de yapmak için aşağıdaki kodları ekledim.

```
use Illuminate\FileSystem\FileSystem;
Route::get('/', function () {
    dd(app(FileSystem::class));
});
```

`FileSystem::class` diyerek o sınıfın bir örneğine erişebiliyoruz.

Service Provider a bir şeyler eklemek için `app()->bind('example', function() { return new \App\Example; });`
Burada example isminde bir şey yok hata verecek. Bunu önlemek için `\app` altına `example.php` isminde bir dosya açıp Example class ını oluşturmak lazım. Artık Laravel Service Container a bir şey ekledik. `dd('example');` ile erişmeyi deniyorum.
```
<?php
    namespace App;
    class Example {
    }
```

Bize çıktı olarak `Example {#206}` geldi. Yani sınıfa erişti ve bize bir nesne döndü.

`dd(app('example'), app('example'));` şeklinde app birden fazla çağrıldığında
```
Example {#206}
Example {#201}
```
2 farklı nesne örneği dönüyor.

Eğer her istediğimizde aynı nesnenin gelmesini istersek `app()->bind` yerine `app()->singleton` denmelidir.
singleton metodunu kullandığımızda singleton tanımladığımız metot sadece 1 kez çalışacak.

Biz Container a bir şey eklemek için app->bind ya da singleton kullanıyoruz. Bunu çağırmak için `app('className')` yeterli. Laravel eğer container da bu şey ekli ise onu döner. Eğer eklenmemişsi bu isimde bir sınıf var mı diye bakar ve onu döner. Bunu anlamak için `app('example')` yerine `app('\App\example')` dediğimizde içeride example diye bir key olmamasına rağmen sınıfı döner.

Eğer biz `singleton('\App\example')` diye bir key eklersek bu sefer class ı aramaz ve bizim eklerken tanımladığımız kodu döner.

Eğer example içerisine bir kurucu ekleyip onunla da farklı bir sınıfı kullansak yine bu işleri Laravel kendi halledecek.

```
class Example {
    protected $foo;
    public function __construct(Foo $foo) {
        $this->foo = $foo;
    }
}

namespace App;

class Foo {
}

Example {#207 ▼
  #foo: Foo {#208}
}
```
Larvel FW sindeki en önemli component app tir. Bizim için Example sınıfından nesne oluşturmasının yanında eğer bu nesnenin de bağımlılıkları varsa bunları da otomatik oluşturur.

Bu özelliği özellikle dış servislere bağlanırken kullanabiliriz. Mesele Twitter Api yi kullandığımızı varsayalarım.
`\app\Services\Twitter.php` isminde bir dosya oluşturup içine gerekli kodları yazalım.
```
class Twitter {
    protected $apikey;

    public function __construct($apikey) {
        $this->apikey = $apikey;
    }
}
```
Bunu Service Container a göndermek için web.php ye
```
app()->singleton('twitter', function() {
    return new \App\Services\Twitter('api_key');
});
```
kodlarını ekledim. Burada new ile tam yol veriliyor. Daha sonra erişmek istersem `$twitter = app('twitter');` demem yeterli.
