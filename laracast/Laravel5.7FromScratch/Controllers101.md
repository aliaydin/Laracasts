## Controllers 101 ##

`routes/web.php` dosyasında şimdiye kadar yaptığımız tanımlamaların hepsin de sayfanın yapacağı işi closure ile yaptık.
 
Küçük projeler için bu olabilir ama proje büyüdüğünde tüm iş katmanını buraya koymak doğru olmayacaktır.

Bunu düzenlemek için `Route::get('/', 'PageController@home');` şeklinde bir satır ekledim. Bu sayede işi controller a pasladım.

`app/Http/Controllers` dizinine `PageController` isminde yeni bir controller eklenmeli. 
Bunu elle eklemek yerine Laravel in artisan aracı sayesinde komutla da yapabiliriz. 
Bu sayede Controller şablonu da hazır gelecektir. Her zaman yeni dosyalar make ile eklenmelidir. Bu projede tutarlılığı sağlar.

Biz `php artisan` yazıp tüm komutları görebiliriz. Gelen listede komut adı ve hangi işi yaptığı yazıyor zaten. 
Daha sonra bize uygun olan komutu bularak kullanabiliriz.

`php artisan make` namespace i bizim için tüm generate işlemlerini yapar. Biz controller oluşturacağımız için
`php artisan make:controller PageController`
komutunu kullanıyoruz.

Controller oluşturulduktan sonra `@home` isimli actionu tanımlamak için `PageController` class ı na home metodunu tanımlamalıyız.
Bu metot `web.php` nin bir önceki versiyonunda closure ile yaptığımız işi yapacak.
Metodu ekleyip sayfayı çağırdıktan sonra herşey önceden `web.php` de tanımlandığı gibi geldi. 

Bu sistem contact ve about sayfaları için de aynen uygulanabilir. Bunun için `web.php` ye

```
Route::get('/contact', PageController@contact);
Route::get('/about'. PageController@about);
```
tanımlamasını yapmak ve bu sayfalar için PageController a bir ilgili metotları eklemek yeterlidir.

Bu sayede daha generic bir `web.php` dosyamız oldu ve tüm işler ilgili controller üzerinden yapıldı.
