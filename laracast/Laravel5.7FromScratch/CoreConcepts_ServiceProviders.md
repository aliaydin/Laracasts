## Core Concepts: Service Providers ##

`app\Providers` klasörü içerisinde ServiceProvider lar bulunur. Bunların 2 temel metodu vardır.

`register()` medotu `ServiceContainer` a birşeyleri bağlamak için kullanılır.
`AppServiceProvider.php` içerisinde `register` metoduna `foo` isminde bir metodu container a ekliyorum.

```
$this->app->singleton("foo", function() {
    return "bar";
});
```

Laravel yüklenirken `congif/app.php` dosyasında `providers` bölümünde bulunan tüm provider ları yükler.

Yükleme işlemi için ilk olarak onların `register()` metotlarını çağırır. Tümü için bu işlem bitince, her birinin boot metotlarını çağırır. `Boot` metodu sırasında FW yüklenmiş olacağı için diğer comp lara ihtiyaç varsa kod buraya yazılmalıdır.

Önceki ders kodlana Twitter servisi `AppServiceProvider` da yüklenebilir.

```
use App\Services\Twitter; ile namespace i ekledim.

$this->app->singleton(Twitter::class, function() {
    return new Twitter('api-key');
});
```
`web.php` içersinde
```
Route::get('/', function (Twitter $twitter) {
    dd($twitter);
```
şeklinde kullanılabilir. Diğer servisler de bu şekilde yüklenebilir. Sonuçta her istek için `AppServiceProvider` çalışır.
Diğer servislerin hepsini AppServiceProvider a eklediğimizde orası gerçekten çok karışacak. Bu yüzden daha okunabilir bir kod için kendi provider ımızı tanımlayalım.

`php artisan make:provider SocialSerciveProvider`

komutu ile `app\Providers` altına `SocialSerciveProvider.php` isimli dosyayı oluşturdum. `make` komutu ile oluşturulduğu için kodlar hazır geldi.

Artık kodumuzu buraya alıp, burayı da ServiceProvider a bind etmeliyiz. Yoksa bu kod kendi kendine durur, kimse tetiklemez.
`config.app.php` içerisinde `providers` kısmının sonunda `ApplicationServiceProviders` kısmı var. Burası uygulama seviyesindeki provider ları ekleyebileceğimiz yer.

`App\Providers\SocialServiceProvider::class,`

kodu ile diğerleri gibi bizim provider ımızı da ekledim.

İstersek kendi Repo muzu yazarak bunu da ServiceProvider a ekleyebiliriz. Repositories klasörüne 2 dosya oluşturdum.
`UserRepository.php` de `UserRepository interfacesi` var. Bunu implemente edecek sınıflar için kontrat sunuyor.

```
interface UserRepository {
    public function create($attributes);
}
```

`DbUserRepository.php` dosyasında ise bir implementasyon var.

```
class DbUserRepository implements UserRepository {
    public function create($attributes) {
        dd("creating the user");
    }
}
```

Bu repoyu `AppServiceProvider.php` de `ApplicationServiceProviders` kısına register ediyorum.

```
$this->app->bind(
    \App\Repositories\UserRepository::class,
    \App\Repositories\DbUserRepository::class
);
```

Intefaceye bir istek gelirse `DbUserRepository` e yönlendir diyorum.

`web.php` de bunu denediğimde

```
Route::get('/', function (UserRepository $users) {
    dd($users);
});
```

`UserRepository interface` i bana `DbUserRepository` sınıfını dönüyor.

`DbUserRepository {#211}`
