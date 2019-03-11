## Core Concepts: Middleware ##

`Middleware` tanımları `app/Http/Kernel.php` içerisinde bulunmaktadır.
Tanımların olduğu kısmın üzerinde açıklama olarak  yazmaktadır.
Kavramı anlamak için kullanıcıdan gelen `http` isteğinin bir soğanın katmanlarından geçtiğini düşünebiliriz.
İstek soğanın çekirdeğine erişinceye kadar geçtiği katmanları `middleware` olarak düşünebiliriz.
`protected $middleware` de yer alan her tanım soğanın bir katmanını oluşturur.

İstek her katmandan geçerken bize cache yapmak, veriyi sessiona yazmak, validation yapmak vb.. için fırsat verir.

Middleware leri `app/Http/Middleware` klasörüne tanımlayabiliriz. 
Bu klasördeki `Authenticate.php` dosyası yetki kontrolü yapar.
Bu sınıf `Illuminate\Auth\Middleware\Authenticate` sınıfını `extend` eder. 

Üst sınıf incelendiğinde `handle` metodunu görürüz.
Her `middleware` mutlaka bir `handle` metodu içerir. `Request` `middleware` lerin her biri için `handle` metodunu tetikler.

`Request` üzerinde bir değişiklik yapılacaksa bu metotta yapılmalıdır. 
İsteği bir sonraki katmana yollamak için işimiz bitince `return next($request);` kodunu kullanmalıyız.

Biz bir önceki derste `HomeController` a ait kurucu metotta `$this->middleware('auth');` kodunu görmüştük.
Bu kodda bulunan `'auth'` keywordu `Kernel.php` deki `protected $routeMiddleware` altındaki 
`'auth' => \App\Http\Middleware\Authenticate::class,` tanımına karşılık gelir.

Biz `/home` sayfasına istek attığımızda `HomeController` a ait `index` metodu tetiklenir.
Index metodu çalışmadan önce `__construct` içerisindeki `$this->middleware('auth');` kodu devreye girer.
Bu kod `\App\Http\Middleware\Authenticate::class` sayfasını tetikler.
Eğer biz kurucudaki kodu kaldırırsak bize kullanıcı girişi yaptırmadan direkt sayfayı açar. 

`Route::get('/home', 'HomeController@index')->name('home');`

Biz bir `middleware` i `controller` dan ya da `route (web.php)` den yapabiliriz. 
Controller da yapmak için kurucu metoda eklenmelidir.
Eğer route ta yapmak istersek `Route::get('/home', 'HomeController@index')->name('home');` kodu sonuna `->middleware('auth')` kodu eklenmelidir.

Yukarıdaki şekilde kullanıcının `authtanticate` olmadan bazı sayfalara girişini engelledik.
Bunun tersi de gereklidir. Login olmuş bir kullanıcı tekrar singup sayfasına girmemelidir.
Bunun için gereken middleware `RedirectIfAuthenticated` tır. Bu da `Kernel.php` de `guest` olarak isimlendirilmiştir.
singup dosyasına tekrar girmemesi için `->middleware('guest')` kodunu eklemek yeterlidir.
Bu kod eğer oturum varsa `/home` sayfasına yönlendirecektir.

```
if (Auth::guard($guard)->check()) {
    return redirect('/home');
}   
```

`Kernel.php` içerisinde `protected $routeMiddleware` altında tanımlı tüm tanımlar incelenmeli.
Burada işe yapayacak pek çok özellik hazır olarak geliyor.

`php artisan make:middleware LogQueries` komutu ile kendi middleware i mizi ürettik.
Artık her istekte bu middleware içerisinde tanımladığımız komutlar çalışacak.
Tabi bunun için tanımlı olan middleware i `Kernel.php` ye eklemek gerekiyor.
`protected $middleware` dizisine `\App\Http\Middleware\LogQueries::class`, ile middleware i ekledim.
Artık her istekte devreye girecek. 

`Kernel.php` içerisinde 2 adet middleware dizisi var. `protected $middleware` dizisi `global` dir ve her istekte çalışır.
`These middleware are run during every request to your application.` kuralı geçerlidir.


`protected $routeMiddleware` ise sadece tanımlı olduğu rotalarda çalışır. Her istekte devreye girmez.
Burada developer hangi kodların her seferde hangi kodların ise bazı rotalarda çalışacağını belirlemeli ve tanımı o diziye yapmalıdır.  