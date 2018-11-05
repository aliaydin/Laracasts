## Routing Conventions Worth Following ##

`Route::get('/projects/create', 'ProjectsController@create');` ifadesinde `projects` bizim `resource` umuzdur.
Yukarıdakı `endPoint` bu `resource` u değiştirmek için kullanılır.

__Rest:__ Bir resource a ne yapılacağını `Http Verb` lerini kullanarak belirlemektir.
`PUT` ve `PATCH` bir resource u UPDATE etmek için kullanılır. Farkları `PUT` sadece bir kısmını (PartialUpdate), `PATCH` tümünü günceller.

Yapılacak tüm istekler:

```
GET /projects (index) // Tüm kayıtları listele
GET /projects/create (create) // Kullanıcıya bilgileri gireceği bir form gösterir
GET /projects/1 (show) // Bir kaydı gösterir
POST /projects (store) // Yeni bir kayıt oluştur. Bunu çağırmak için öncesinde bir form göstermek gerekir.
GET /projects/1/edit (edit) // Kullanıcıya bilgileri güncelleyebileceği bir form gösterir
PATCH /projects/1 (update) // Bir kaydı günceller
DELETE /projects/1 (destroy) // Bir kaydı siler
```

Yukarıda tanımları rotaya çevirmek istediğimizde aşağıdaki rotalar oluşur.

```
Route::get('/projects', 'ProjectsController@index');
Route::get('/projects/create', 'ProjectsController@create');
Route::get('/projects/{project}', 'ProjectsController@show');
Route::post('/projects', 'ProjectsController@store');
Route::get('/projects/{project}/edit', 'ProjectsController@edit');
Route::patch('/projects/{project}', 'ProjectsController@update');
Route::delete('/projects/{project}', 'ProjectsController@destroy');
```

`php artisan route:list` komutu ile web.php de tanımlı rotalar görebiliriz.

Yukarıdaki 7 tanımı tek satırda yapmak için `Route::resource('projects', 'ProjectsController')` yeterlidir. `/projects` e gelecek tüm `REST` istekleri için `ProjectsController` a gir ve oradaki default metotları kullan.

Yeni gelen metotlar için Controller da düzenleme yapmak gerekir. Ama `php artisan make:controller PostsController -r ` komutunu kullandığımızda tüm metotları içeren boilerplate kod gelecektir. Bu komutlar RestFull convention a uygun kodlardır ve bunlara bağlı kalmak bestPractice dir.

 `php artisan help make:controller` ile bu komutun kullanımı hakkında yardım alabilirsin. Aynı komutu  `php artisan make:controller PostsController -r -m Post` şeklinde kullandığımızda bizim yerimize bir adet Eloquent Model ekler ve bu modeli controlllarda kullanmak için gerekli use komutunu `use App\Post` da kendi ekler.

Yukarıdaki hazır yapılar sayesinde aslında işin büyük kısmı bizim için hazır halde geliyor. Bize çok az kod yazarak çok fazla iş yapmak kalıyor.
