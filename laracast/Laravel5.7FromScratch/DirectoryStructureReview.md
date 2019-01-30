## Directory Structure Review ##

`Sadece ilk planda lazım olan dosya ve klasörler incelenecek.`

`.env` FW ile ilgili tüm ayarlar burada tutuluyor. Bu dosya `.gitignore` a ekli. Sunucuya gönderilmiyor. 
Sonucuda bunun yerine config altındaki ayarlar kullanılıyor.

`artisan` Bu dosya `php artisan` yazdığımızda çalışan uygulamaya ait dosya.

`composer.json` Projemizin ihtiyaç duyduğu kütüphane isimleri burada bulunur. Bu dosya ile proje kurulabilir.

`composer.lock` Tüm bağımlılıkların o anki versiyonunu tutar. Bu dosya git e gönderilir ve FW yi kuran herkes bizimle aynı sürümü yükleyebilir.
Bu proje başka bir makinede kurulmak istendiğinde bu dosya okunarak projenin o an ihtiyaç duyduğu versiyonlar indirilir.

`package.json` Front-End kütüphanelerinin bağımlılıklarını bu dosyadan yönetiliyor.

`\vendor` Tüm composer dependencies in yüklendiği yer.

`\routes` `web.php` ile rotalama işlemleri yapılır. `comsole.php` ile artisana Closure yazılabiliyor. `api.php` Api rotalama burada.

`\public` Tüm resim dosyaları bu klasör altına yüklenmelidir. 
Buradaki .js ve .css `resource` taki dosyaların derlenmiş hali. Bu derleme işlemini ise `webpack.mix.js` dosyası yapıyor.

`\database` içerisinde migration dosyaları bulunur. Ayrıca oluşturulan tabloları beslemek için kullanılacak seeds dosyaları da bu klasöre eklenmelidir.  

`\config` Tüm ayarlar bu klasörde. `app.php` ile uygulama ile ilgili ayarlar yapılır.
Bu ayarlar yapılırken `'env' = 'production'` şeklinde sabit tanımlarla değil de `.env` dosyası üzerinden okuyacak şekilde yapılır.
`'env' => env('APP_ENV', 'production')` kodu env tanımlı APP_ENV i kullan, eğer bulamazsan 'production' kullan demek.

`\bootstrap` Laravel FW bu klasördeki kodlar ile ayağa kalkıyor.

`\app` Tüm Controller ve Modellerimiz burada tutulacak. 
Buradaki `Middleware\` gelen requestleri ilk karşılayan yer. 

Kullanıcıdan gelen tüm istekler `Middeware` deki tüm katmanlardan geçer. Bize her katmanda isteğe cevap verme şansı verir.
Middleware deki hangi katmanların kullanılacağı `Kernel.php` deki `$middleware` değişkeninde tanımlıdır.

`Providers\` içerisinde, `vendor` içerisindeki paketlerin Laravel e bağlanmasını sağlayan kodlar bulunur.