## A Full Registration System in Seconds ##

Şimdiye kadar tasarladığımız sayfalar için bir koruma koymadık.
Herhangi bir kullanıcı linke girip Proje ve Task ekleyebilir.

`RealLife` projelerinde bu böyle olmayacaktır. Ancak yetkisi olan kullanıcılar işlem yapabileceklerdir.

`php artisan make:auth` komutu ile Laravel tarafından hazırlanmış register ve login sayfalarını oluşturabiliriz.

Komutu çalıştırdığımda daha önceden oluşturulan dosyaları ezmeden evvel onay istiyor. (`layouts/app`)

`Auth` mekanizmasını sağlamak için `web.php` içerisine `Auth::routes();` satırını ekledi. 
Bu kod gerekli rotaları web.php ye ekliyor.

Eklenen rotaları görmek için php artisan `route:list` komutu kullanılabilir. 

Ya da `vendor/laravel/../Router.php` dosya içerisindeki `auth` metodu incelenebilir.

Ayrıca `app/http/Controller` altına `Auth` isminde bir klasör açıp gerekli tüm controller ları buraya ekledi.

Bir projede isteksek `DB` olarak `Mysql` dışındaki alternatifleri de kullanabiliriz.

`File Based DB` olarak `sqlite (esquLayt)` kullanılabilir. `DB` lerle ilgili ayarlar `config/database.php` altında.

Hangi `DB` yi seçeceğimizi ise `.env` içerisinden belirleyebiliriz ve temel config bilgisini buradan verebiliriz.

Seçilecek `DB` için gerekli ayarları `database.php` den öğrenip bu bilgileri `.env` içerisinden vermeliyiz.

`.env` içerisindeki `DB_CONNECTION=mysql` satırını `DB_CONNECTION=sqlite` olarak değiştirdim.

Veritabanı `database/database.sqlite` dosyasında tutulacak. (Istenirse bu ayar `.env` den değiştirilebilir.) 
Bunun için bu dosyayı oluşturmam lazım.

`sqlite` için `.env` deki ayarlar, bu ayarlar dışında `DB` ile ilgili diğer ayarlar silinmelidir.

```
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
```

`DB` de ayarlandığına göre artık `php artisan migrate` ile tüm komutları yeni `DB` için çalıştırabiliriz.

Bir kullanıcı kaydedildiğinde `RegisterController` içerisindeki `register` metodu çalışıyor.

`make:auth` `web.php` ye `Route::get('/home', 'HomeController@index')->name('home');` rotasını ekledi.

`HomeController` incelendiğinde `__construct` metodunda `$this->middleware('auth');` kodu görülür.

Bu kod ile herhangi bir metot çalıştırılmadan önce `middleware` ile koruma sağlanır.
