## Core Concepts: Service Container and Auto-Resolution ##

__Laravel Service Container:__ Laravel FW nin en temel kavramlarından birisidir. Basitçe bir oyuncak kutusu gibi düşünülebilir.

`ProjectsController` daki `show` metodunda `public function show(Project $project)` koduyla,
 Project modelini `Route Model Binding` kullanarak alabiliyoruz. 
 Aslında burada sadece `Route Model Binding` kullanılmıyor. Ayrıca `Servive Container Component` te işin içerisinde.
   
Mesela `show` metoduna parametre olarak `FileSystem` sınıfını vermek isteyeyim.

Bunun için öncelikle `use Illuminate\FileSystem\FileSystem;` ile namespace i ekledim. 
Daha sonra metodu parametre olarak gönderip `dd` ile ekrana bastırdım.

```
public function show(FileSystem $file) {
    dd($file);
```


`projects/1` sayfası açıldığında ekrana gelen çıktı. `Filesystem {#104}`

Yani metoda parametre olarak verdiğimiz tipteki nesneden Laravel bizim için bir tane otomatik oluşturup.
Laravel bunu yapabilmek için arka planda 2 teknoloji kullanıyor.

1. __AutoResolving:__ Php Reflection kullanarak `Type Hinting` ile verilen sınıf isminden sınıfa ulaşıyor ve ondan bir nesne oluşturuyor.
2. __Service Provider:__ key-value ikilisi şeklinde servisler bir yerde duruyor. 
Laravel istenen sınıf orada ise onu resolve ederek bize geri veriyor.

Denemeler yapmak için en güzel yer web.php dir. 
Sonuçta tüm istekler buradan geçer ve isteği keserek kendi kodlarımızı ekleyebiliriz.

Service Provider a erişmek için `app()` metodunu kullanabiliriz. `app` bize `Service Container` ı ifade eder.
Bu metot aslında `resolve()` metodunun aliasıdır. Aynı işlemi `web.php` de yapmak için aşağıdaki kodları ekledim.

```
use Illuminate\FileSystem\FileSystem;
Route::get('/', function () {
    dd(app(FileSystem::class));
});
```

`FileSystem::class` diyerek o sınıfın bir örneğine erişebiliyoruz.

Service Provider a bir şeyler eklemek için `bind()` metodu kullanılabilir.
`app()` ile uygulamaya eriştikten sonra ona ait metotlara erişebiliriz. 

`app()->bind('example', function() { return new \App\Example; });`

Yukarıdaki kodda `example` ismiyle kutuya yeni bir oyuncak atıyoruz. 

Example isminde bir sınıf olmadığı için ilk başta hata verecektir. 
Bunu önlemek için `\app` altına `example.php` isminde bir dosya açıp `Example class` ını oluşturmak lazım. 
Artık `Laravel Service Container` a bir şey ekledik. `dd('example');` ile erişmeyi deniyorum.

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

Biz Container a bir şey eklemek için `app()->bind(key)` ya da `app()->singleton(key)` kullanılabilir. 
Eklediğimiz şeyi kullanmak için ise `app('key')` yeterli. Laravel eğer container da bu şey ekli ise onu döner. 
Eğer `app('key')` ile aranan `key` eklenmemişse, bu sefer arama işlemi `Service Container` dışına çıkar. 
Yeni bir arama işlemi başlar ve bu isimde bir sınıf var mı diye bakar ve onu döner. 

Bunu anlamak için `app('example')` yerine `app('\App\example')` dediğimizde,
kutu içerisinde `\App\example` diye kaydedilmiş bir `key` var mı diye bakar, eğer yoksa bunu sınıf olarak çağırmayı dener.

Eğer biz `singleton('\App\example')` diye bir key eklersek bu sefer class ı aramaz ve bizim eklerken tanımladığımız kodu döner.

Eğer example içerisine bir kurucu ekleyip, `Constructor Injection` ile Example sınıfına kullanması için 
bir sınıf eklersek yine bu işleri Laravel kendi halledecek.
Bu şekilde bizim için tüm `Dependency Injection` işlemleri Laravel tarafından düzenlenmiş olur.  

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
Larvel FW sindeki en önemli component app tir. 
Bizim için Example sınıfından nesne oluşturmasının yanında eğer bu nesnenin de bağımlılıkları varsa 
bunları da otomatik oluşturur.

Example sınıfının ihtiyaç duyduğu Foo sınıfı Type Hinting ile verildi.
O an Laravel bu sınıfı Reflection ile çözerek bize Foo sınıfının bir örneğini dönüyor.

Eğer Foo sınıfı da başka sınıflara ihtiyaç duysa sistem aynı şekilde işlemeye devam edecek.


Bu özelliği özellikle dış servislere bağlanırken de kullanabiliriz. Mesele Twitter Api yi kullandığımızı varsayalarım.
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

kodlarını ekledim. 
Burada new ile tam yol veriliyor. Daha sonra erişmek istersem `$twitter = app('twitter');` demem yeterli.

Eğer `Twitter sınıfına` `Twitter $twitter` şeklinde erişirsem o zaman `app()->bind()` kısmını atlar ve direkt sınıfa gider.

Twitter servisi için `apikey` bilgisi genelde `config` te olur.
Bu şekilde `config('services.twitter.api_key');` bir kullanım daha uygun olur.

`Services` ismindeki klasör altına projenin ihtiyaç duyduğu `class` lar eklenebilir.  