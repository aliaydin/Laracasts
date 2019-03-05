## Core Concepts: Configuration and Environments  ##

Laravel'in configuration ayarları `config` klasörü içerisindeki dosyalarda tutulur.

`config` klasöründeki dosyaların büyük kısmında `env()` helper metodu kullanılır.
Bu `helper` PHP deki `$_ENV super global` ini temsil eder.

Laravel yüklendiğinde `.env` dosyası içerisindeki tüm `CONST` ları `$_ENV` içerisine yükler.

`config/mail.php` içerisindeki `'driver' => env('MAIL_DRIVER', 'smtp')` kodu incelendiğinde,
`env` metodu ilk parametre olarak aldığı `MAIL_DRIVER` key ini `.env` den yüklediği `$_ENV` içerisinde arar.

Eğer bu `key` tanımlı ise onun değerini getirir, aksi halde 2. parametrede kullanılan `, 'smtp')` değerini yükler.

Genel kural olarak `env` metodu ile erişeceğimiz `key` ler `.env` içerisinde tanımlanmalıdırlar.

Örneğin `MAIL_FROM_ADDRESS` `key` i `.env` içerisinde tanımlı değil. Eğer bu kullanılacaksa bu veri tanımlanmalıdır.
Tanımlamak yerine, ikinci parametreye gidilerek `default value` değiştirilmemelidir.

`.env` dosyası `.gitignore` da tanımlıdır. Dolayısı ile sunucuya gitmez. 
Bu özelliği kullanarak `privateKey` leri `.env` içerisinde tanımlayıp `config` ten ona referans verilmelidir.

Kodu alan diğer yazılımcılar da kendi makinelerinde kendi `privateKey` lerini `.env` içerisine tanımlayabilirler.

Ayrıca `dev` ve `live` ortamları için de ayrı `config` lere sahip olmuş oluruz.

Mesela `.env` dosyasında `APP_DEBUG=true` iken bu `production` a çıkıldığında `false` olmalıdır.

`config` dosyalarını kullanmak için bir test yapmak istediğimizde, önceki derslerde eklediğimiz `Twitter` sınıfını kullanabiliriz.

`Twitter` sınıfı için `api-key` `hadcoded` tanımlanmıştı. O ders işlenirken bunun daha iyi bir yolu olduğu söylendi.

Buradaki daha iyi yol bu veriyi `config` üzerinden almaktır.

Uygulamaya ait ayarları `config` te tutmak her zaman güzel bir yaklaşımdır.

`config` içerisindeki bir dosyadan veri okumak için `config()` helper i kullanılır.

3rd servisler için `config.services.php` dosyayı uygun bir yer. Ama `paypal.php` gibi ayrı bir dosya da kullanılabilir.

`config.services.php` içerisinde aşağıdaki tanımlamayı ekledim. Artık `api-key` bilgisini `config` ten alacağım.
 
```
 'twitter' => [
        'key' => 'public-key', // env()
        'secret' => 'private-key'
    ]
```

Bu veriyi okumak için `config('services.twitter.secret)` komutu kullanılır. `dosya.dizi.key` şeklinde bir desen var.

`SocialServiceProvider.php` içerisindeki kod aşağıdaki gibi düzenlenmelidir.

```
    public function register()
    {
        $this->app->singleton(Twitter::class, function() {
            // Core Concepts: Configuration and Environments
            return new Twitter(config('services.twitter.secret'));

            // return new Twitter('api-key');
        });
    }
```

Verileri `config.services.php` içerisine `hardcoded` yazmak doğru değil. Bu bilgileri de `.env` içerisine atmalıyız.
Yani kod `config` ten almalı ve `config` te `.env` dosyasından.

`.env` dosyasının sonuna aşağıdaki kod eklenir.

```
TWITTER_KEY=public-key
TWITTER_SECRET=private-key
```

Ve `config.services.php` dosyası da aşağıdaki gibi düzenlenir.
```
    'twitter' => [
        'key' => env('TWITTER_KEY'),
        'secret' => env('TWITTER_SECRET')
    ]
```

`php artisan config:cache` sadece `production` da kullanılmalıdır. `developmen` ta kullanırsen sürekli `cache` tuzağına düşersin. 
Tüm config dosyalarını alıp tek bir dosyaya dönüştürür ve hızlıca yüklenmesini sağlar.

Eğer `config` klasörüne yeni bir dosya eklersen bunu görebilmesi için `php artisan config:clear` yapman gerekebilir.

`production` da hız için sürekli `cache` li çalışmak gerekiyor. Eğer dosya değişirse `cache` komutunu 1 kez daha çalıştır.
 