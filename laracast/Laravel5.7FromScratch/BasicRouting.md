## Basic Routing ##

`php artisan serve`

komutu ile Laravel development serverı (Aslında PHP build-in sunucu) başlattım. http://127.0.0.1:8000


Tüm rotalar

`/routes/web.php`

altında tutuluyor. Yeni bir sayfa için oraya sayfayı tanımlayıp, çalıştırabiliriz.

```
Route::get('/', function () {
    return view('welcome');
});
```

Route sınıfına ait static get metodu. Gelen get isteğini al ve şu view dosyasını aç.
Bu metot 2 parametre alıyor. 1. si isteğin yapıldığı adres (yani browser a yazılan adres), 2 si ise bir callback fonksiyonudur.

Implementasyonda return view ile seçilen view dosyasını geri dönüyor.

Tüm view dosyaları ve diğer css, js dosyaları da (resources) altında

`/resources/views/`

altında bulunur. View dosyaları .blade.php uzantılıdır. 
Bu sayede .php derleyici çalıştıktan sonra sayfayı 1 kez de template engine render eder.

Biz

`/routes/web.php`

dosyasına contact isminde 2. bir sayfa eklediğimizde

```
Route::get('/contact', function () {
    return view('contact');
});
```

Eğer view tanımlı değilse laravel in detaylı hata sayfası gösterilir.

`/resources/views/`

altına `contact.blade.php` isminde yeni bir view ekleyerek hatayı giderebilirsin.

Eğer routes teki tanımlamayı kaldırırsak yapılan get isteğine karşılık bir rota bulunmadığı için 404 hatası alırız.

Aynı rotadan 2 tane tanımlanırsa en sonra tanımlanan rota geçerli olur.