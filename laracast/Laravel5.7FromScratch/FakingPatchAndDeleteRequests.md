## Faking PATCH and DELETE Requests ##

Formlar için sadece `POST` ve `GET` istekleri tanımlıdır. 
`PATH` ve `DELETE` Verb lerini geçerli kılmak için Laravel de ufak bir trick kullanmak gerekir.

Düzenleme formunu göstermek için `edit` actionu kullanılacak.

`Route::get('/projects/{project}/edit', 'ProjectsController@edit');` rotasındaki `{project}` bilgisine `edit` metodunda `$id` ile erişebiliriz.

Burada `edit($id)` yerine istediğimiz değişkeni kullanabiliriz. Sonuç olarak bu parametre `url` den gelen `wildcard` bilgisini temsil ediyor.

`edit` metodu geriye `return view('projects.edit'); // ControllerName.ViewName` döndürecektir. 

View ismi verirken `ConstollerName.ActionName` kullanmak gerekir.
Eğer `Controller` da bir domain altında ise onun da başına `DomainName.` gelmelidir.

`var_dump(); die;` işlemi için Laravel `dd()` metodunu eklemiş. Basit debug için kullanılabilir. `dieAndDump` anlamına gelir.

`edit` metodu içerisinde `id` si gönderilen kayda erişmeli ve bu kaydı `projects.edit` view ine göndermeliyiz.

Geleneksel olarak metoda parametre olarak verilen `view($id)` `$id` yi alıp DB de bu kaydı aramalıyız. (Daha iyi bir yolu var.)


`Project::find($id);` komutu ile `projects` tablosunda `id` si verilen kayda ulaşabiliriz.

Veriyi bir değişkeni atıp view e gönderebiliriz.

```
$project = Project::find($id);
return view('projects.edit', compact('project'));
```

Compact fonksiyonu parametre olarak verilen stringle eşleşen değişken değerini alır ve bu değişkeni ve buna ait veriyi dizi olarak döner.

`View` de bu değişkene `view` metoduna parametre olarak verdiğimiz isimle erişebiliriz.

Formu gönderirken `POST` kullanıp forma gizli bir alan eklemeliyiz.

`{{ method_field('PATCH') }}` kodu HTML de hidden bir form olanı oluşturur. 

Post edilen Form içerisindeki hidden alan `<input type="hidden" name="_method" value="PATCH">` kodu ile gider.

Bu kodu eklemeden formun metoduna direkt `action="PATCH"` yazacak olursak. Html `PATH` i çözemeyeceği için varsayılan `GET` isteğini çalıştırır.

Laravel burada hidden la gönderilen `_method` fieldine bakarak isteğin aslında `PATCH` olarak gönderildiğini anlar.
 

`Project::find($id)` kodu bize bir nesne döner ve biz bu nesneyi kullanarak `view` de `$project->title` ile nesne property lerine erişebiliriz.

Edit fomunun actionuna ne yazılacak? 

`php artisan route:list` ile kontrol ettiğimizde `PUT/PATCH` isteğinin sadece `projects.update` rotasında karşılığı olduğunu görürüz.

Biz de formu bu rotaya göndereceğiz. `projects/{project}`

Edit lenen kayıtların formunun action metodunda `/projects/{{ $project->id }}` rotası bulunur. 

Bu rota bizi `update($id)` metoduna götürür.

Bir formdan gelen tüm bilgileri görebilmek için `request()->all()` komutunu kullanabiliriz.
 
`request()` helper i sayesinde değişkenlere kolayca erişebiliriz. Örneğin `request('title')` gibi.

Update metodu içerisinde edit metodunda yapıldığı gibi `find()` ile kaydı buluruz.
 
Çünkü güncelleme bu kayıt üzerinde yapılacak. 

Biz kayda erişip bunu bir değişkene attığımızda o kayıt elimizde olur.

Daha sonra bu kaydı güncelleyip `save()` ile bilgileri DB ye kaydederiz.

```
$project = Project::find($id);

$project->title = request('title');
$project->description = request('description');

$project->save();
```

Eloquent te bir kayda `find()` ile erişim o kaydın objesini bir değişkene atıp field ları değiştirip `save` ile güncelleyebiliriz.

Normalde `update` metodu dönüşünde `show` metodu çağrılır ve güncellenen kayıt gösterilir. 

Şu an bu sayfa yok diye `index` e yolluyorum.

`return redirect('/projects');`