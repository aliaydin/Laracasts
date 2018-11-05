## Directory Structure Review ##

Laravel dosya ve dizin yapısını incelenecek. Önemsiz ya da şu an için önemli olmayanların detayına girilmeyecek.

`.env` Bu dosyayı önceden incelemiştik. FW ile ilgili tüm ayarlar burada tutuluyor. Bu dosya `.gitignore` a ekli.

`artisan` Bu dosya `php artisan` yazdığımızda çalışan uygulamaya ait dosya.

`composer.json` Projemizin ihtiyaç duyduğu kütüphane isimleri burada bulunur. Bu dosya ile proje kurulabilir.

`composer.lock` Tüm bağımlılıkların o anki versiyonunu tutar. Bu dosya git e gönderilir ve FW yi kuran herkes bizimle aynı sürümü yükleyebilir.

`\vendor` Tüm composer dependencies in yüklendiği yer.

`\routes` `web.php` ile rotalama işlemleri yapılır. `comsole.php` ile artisana Closure yazılabiliyor? `api.php` Api rotalama burada.

`\public` Tüm resim dosyaları bu klasör altına yüklenmelidir. Buradaki .js ve .css `resource` taki dosyaların derlenmiş hali.

`\config` Tüm ayarlar bu klasörde.

`\bootstrap` Laravel FW bu klasördeki kodlar ile ayağa kalkıyor. İlk çalışan yer burası?

`\app` Tüm Controller ve Modellerimiz burada tutulacak. Buradaki `Middleware\` gelen requestleri ilk karşılayan yer. `Providers\` içerisinde, `vendor` içerisindeki paketlerin Laravel e bağlanmasını sağlayan kodlar bulunur.
