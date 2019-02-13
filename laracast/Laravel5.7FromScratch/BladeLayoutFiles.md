## Blade Layout Files ##

`about` sayfası eklenip oraya bir navigasyon eklendi. Daha sonra bu menu diğerlerine de eklendi.
Yani menu gibi ortak bir şeyi her sayfaya tek tek eklemek zaman kaybı. 
Ayrıca bu menüdeki her değişiklik için tüm sayfalarda değişiklik yapmak gerekiyor. Bakım maliyeti yükseliyor.
Bunu aşmak için tüm sayfalarda kullanılacak bir `template` e ihtiyaç var.
Laravel de buna `Layout` deniyor. .net mvc de de benzer bir bir yapı vardı.

__Blade:__ `.blade.php` uzantılı dosyalar php de derlendikten sonra sayfayı render eden bir `template engine` dir.
Layout içerisine sayfanın yapısı kodlanır. Layout dosyası için genelde layout ya da master gibi isimler verilir.

Sayfa yapısı tanımlandıktan sonra bu yapı içerine gelecek dinamik kısımlar için

`@yield('content')`

komutu eklendi. Burada content isimli bir bölümün buraya ekleneceği söyleniyor.

Layout dosyasında `@yield` ile tanımlı kısımları kullanabilmek için 
o Layout u kullanan dosya da o yield için bir `section` tanımlanmalıdır.

Layout dosyasını kullanacağımız dosyanın başına

`@extends('layout')`

demek yeterlidir. Eğer klasör içerisinde bir yapı varsa `dirName.fileName` olarak çağrılır. `.` Yerine `/` da kullanılabilir.

Layout dosyaları için root `/resources/views` klasörüdür.

```
@section('content')
..
@endsection
```

.. kısmına yazılan herşey her şey layout dosyasındaki `@yield('content')` ile işaretlenmiş yere eklenecektir.

Her sayfa için ayrı title koymak için `@section('title')` kullanılabilir.

Hatta `@yield('title', 'Default Title')` ile varsayılan title da verilebilir.
`Welcome.blade.php` de `@section('title')` kullanılmadığı için bu kısma layout tan gelen title eklenecektir.

Aynı şekilde section kullanılırken de 2. parametre ile değer verilebilir.

`@section('title', 'contact')`

Yukarıdaki kullanımda `@endsection` kullanılmaz.

Zaten herşey fonksiyona verilen parametre ile tamamlanmaktadır. Blok açılmadığı için kapatmaya gerek yok.
