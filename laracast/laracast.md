

Video3: Basic Routing

Tüm rotalar /routes/web.php altında tutuluyor. Yeni bir sayfa için oraya sayfayı tanımlayıp, çalıştırabiliriz.

Route::get('/', function () {
    return view('welcome');
});
Route sınıfına ait static get metodu. Gelen get isteiğini al ve şu view dosyasını aç.
Bu metot 2 parametre alıyor. 1. si isteğin yapıldığı adres (yani browser a yazılan adres), 2 si ise bir callback fonksiyonudur.
Implementasyonda return view ile seçilen view dosyasını geri dönüyor.

Tüm view dosyaları /resources/views/ altında bulunur. View doyaları .blade.php uzantılıdır.
Biz contact isminde 2. bir sayfa eklediğimizde /routes/web.php ye
Route::get('/contact', function () {
    return view('contact');
});
satırını ekleriz.

Ben /var/www/html altına Apache altında çalışsın diye attım. Virtual host tanımı ile uğraşmamak için oraya yolladım.
Ama .htacces te sorun çıktı. /contact için yönlendirme yaptığımda hata aldım. Bunun için yerel sunucuyu ayağa kaldırdım.

php artisan serve
Laravel development server started: <http://127.0.0.1:8000>

Eğer view tanımlı değilse laravel hatayı fırlatan güzel bir sayfa gösterir.
/resources/views/ altına contact.blade.php isminde yeni bir view ekleyerek hatayı giderebilirsin.

Eğer routes teki tanımlamayı kaldırırsak 404 hatası alırız.

Video4: Blade Layout Files
about sayfası ekleyip oraya bir navigasyon ekledik. Daha sonra bu menu yu diğerlerine ekledik. Bu menüdeki her değişiklik için tüm sayfalarla uğraştık.
Bize PartialInclude imkanı verecek bir yapı lazım. Laravel de buna Layout deniyor. .net mvc de de benzer bir bir yapı vardı.
Blade: kodlar php de derlendikten sonra sayfayı render eden bir yapıdır. Biz Layout içerisine sayfanın yapısını kodlayacağız.
Dinamik yerler için @yield('content') gibi bir bölüm ekledik.
Layout dosyasını kullanacağımız dosyanın başına @extends('layout') demek yeterli. Eğer klasör içerisinde bir yapı varsa 'dirName.fileName' olarak çağrılır.
Layout dosyaları için root /resources/views klasörüdür.
@section('content') .. @endsection: Bu kısımlara yazacağımız her şey layout dosyasındaki content ile işaretlenmiş yere eklenecektir.
Her sayfa için ayrı title koymak için @section('title') kullanılabilir. Hatta @yield('title', 'Default Title') ile varsayılan title da verilebilir.
Aynı şekilde section kullanılırken de 2. parametre ile değer verilebilir. @seciton('title', 'contact') gibi..
Yukarıdaki kullanımda @endsection kullanılmaz. Zaten herşey fonksiyona verilen parametre ile tamamlanmaktadır. Blok açılmadığı için kapatmaya gerek yok.
