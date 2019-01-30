## Form Handling and CSRF Protection ##

Bu bölümde yeni projects tablosuna yeni bir proje eklemeyi göreceğiz.

Yeni bir kayıt oluşturacak formu göstereceğimiz sayfayı yapmadan önce onu `web.php` de tanımlamalıyız.

`Route::get('/projects/create', 'ProjectsController@create');` yeni bir kayıt üretecek sayfanın rotası bu şekilde olmalıdır.

Burada common conventions yeni bir kayıt oluşturacak formu @create metoduna eklemektir. 

Bu rota eklendikten sonra `ProjectsController` a `create` metodu eklenmelidir. Ve sonra bu metot bir view göstereceği için o view tasarlanmalıdır. 
View de form tanımlandı ve action kısmı `/projects` olarak belirlendi. Bu rotaya daha önce bir `GET` tanımı vardı. 
Fakat form `POST` edildiği için bu request o rotayı kullanmayacak. Bu yüzden `POST` u karşılayacak bir rota eklenmeli.

`web.php` ye `Route::post('/projects', 'ProjectsController@store');` kodu eklendi. Burada önceden yazdığımız create ve şimdi kullandığımız store metotları Laravel için convention dır. Metot isimlerine sadık kalmak gerekir.

Formdan gelen bilgileri almak için `return request()->all();` metodunu kullanıyoruz. Sadece tek bir alana ait bilgiyi almak için  `request('fieldName');` yazmak yeterli.

Tüm ayarlamaları yapıp isteği yaptığımızda bize `419` hatası döndü. Bu hata authToken ın eksik olmasından kaynaklanır. 
Bunu düzeltmek için forma `{{ csrf_field() }}` kodu eklenir. Bu kod her istek için bize farklı bir token oluşturur. 
Html konuda baktığımızda `_token` isminde hidden bir alan var.
CSRF koruması Laravel le birlikte varsayılan olarak gelir. Ama istenirse kapatılabilir.

Gelen bilgileri DB ye kaydetmek için `Project` sınıfından bir nesne alıp verileri onun üzerinde tutmalı ve sonra kaydetmeliyiz.
Biz daha önce `Project::all()` diyerek bu sınıfın static all metodunu kullanmıştık.

İşlem bittikten sonra tüm projelerin listelendiği sayfaya dönmek için `redirect` metodunu kullanabiliriz. redirect default GET kullanır.

```
$project = new Project();
$project->title = request('title');
$project->description = request('description');
$project->save();
return redirect('projects');
```

`\Http\kernal.php` içerisindeki `$middlewareGroups` kısmındaki `web` keyinde tanımlı metotlar `web.api` da tanımlı rotalarca kullanılır. 
Bu tanımlamadaki `VerifyCsrfToken` kısmı bize 419 hatasını verdiren kısım. Eğer bunu kaldırırsak token göndermeden de post yapabiliriz. 
Bu bize Laravel tarafından sağlanmış `free security protection out of the box`.

Eğer istersen bu middleware lerin de koduna bakabilirsin. Her middleware handle adında bir metot içerir. 
Implementasyon `VerifyCsrfToken.php` dosyası içerisinde. Kod incelendiğinde unitTest çalışıyorsa bu kontrolun atlandığı görülebilir. 
Formdan gelen tokenı karşılaştırmak için forma bu token yazıldığı sırada bir kopyası da sessiona yazılır. 
Daha sonra isteğin gittiği tarafta bu iki değer karşılaştırılır.

__CSRF (Cross-Site Request Forgery):__ Farklı bir domainden bizim scriptimize istek yapılması anlamına gelir. 
Bu güvenlik önlemi zaten Laravel tarafından sağlanıyor. 
Kullanıcıya düşen tek şey bunu formlara `{{ csrf_field }}` kodunu eklemek.
