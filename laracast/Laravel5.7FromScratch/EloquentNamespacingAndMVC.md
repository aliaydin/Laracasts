## Eloquent, Namespacing, and MVC ##

Migrate komutu kullanıldığında Laravel çalıştırılmamış migration dosyası var mı diye bakar ve bulur bunların up metodunu çalıştırır.

Eloquent, Laravel in Active Record Pattern implementasyonudur.

`php artisan make:model Project` komutu ile projects tablosunu yöneteceğimiz class ı oluşturduk.

Projects tablosunun tek bir kaydını tutacağı için class a Project adını verdik. projects tablosu için Project modeli.

Komut çalıştırıldıktan sonra `app` klasörü içerisine `Project.php` adıyla model dosyası oluştu. 
View ve Controller in yerini daha önce görmüştük. Bu bilgiyle birlikte MVC tamamlandı.

`php artisan tinker` komutu ile terminalde Laravel kullanabileceğimiz bir program çalıştırabiliriz.

Biz tinker içerisinde App\Project dediğimiz an artık Project modeline erişmiş durumdayız.
Sınıfta nımlı static all(), first(), latest() gibi metotları çalıştırabiliriz.

`\App\Project::all()` komutu projects tablosundaki tüm kayıtları verir.
 
Aşağıdaki komutlarla Project tablosuna bir kayıt ekledik.

```
>>> $project = new App\Project;
=> App\Project {#2891}
>>> $project->title = 'My first Project'
>>> $project->description = 'My first desc';
>>> $project
=> App\Project {#2891
     title: "My first Project",
     description: "My first desc",
   }
>>> $project->save()
=> true
```

Ayrıca tabloyu sorgulamak için aşağıdaki komutlar kullanılır.

```
>>> $project->first()
=> App\Project {#2890
     id: 1,
     title: "My first Project",
     description: "My first desc",
     created_at: "2018-11-03 12:28:29",
     updated_at: "2018-11-03 12:28:29",
   }
>>> $project->first()->title
=> "My first Project"
```

Ayrıca 2. bir kayıt ekleyerek collection üzerinde işlem yapabiliriz.

`App\Project::all()` tüm kayıtları getirir.

`App\Project::all()->map->title` ie tüm kayıtların title bilgisini çekebiliriz.

```
>>> App\Project::all()
=> Illuminate\Database\Eloquent\Collection {#2904
     all: [
       App\Project {#2903
         id: 1,
         title: "My first Project",
         description: "My first desc",
         created_at: "2018-11-03 12:28:29",
         updated_at: "2018-11-03 12:28:29",
       },
       App\Project {#2913
         id: 2,
         title: "My second",
         description: "Desc second",
         created_at: "2018-11-03 12:45:32",
         updated_at: "2018-11-03 12:45:32",
       },
     ],
   }
>>> App\Project::all()[1]
=> App\Project {#2916
     id: 2,
     title: "My second",
     description: "Desc second",
     created_at: "2018-11-03 12:45:32",
     updated_at: "2018-11-03 12:45:32",
   }
>>> App\Project::all()[0]->title
=> "My first Project"
>>> App\Project::all()->map->title
=> Illuminate\Support\Collection {#2895
     all: [
       "My first Project",
       "My second",
     ],
   }
```

Projects modelini kullanmak için `php artisan make:controller ProjectsController` ile bir controller oluşturdum.

Daha sonra bu controller için bir rota tanımı yaptım.  `Route::get('/projects', 'ProjectsController@index');`
Buradaki index metodu Controller içerisinde tanımlanmalıdır.

Tinker da kullanılan komutların tamamı .php de de aynen geçerli. Projects controller da bu bilgileri kullanarak kayıtlar çekilebilir.

`$projects = \app\Project:all();` ile tüm kayıtları çektim.
Burada `\app\Project` ile app klasörü altındaki Project.php dosyasına erişiyorum. 
Bunu PSR-4 autoloading standartını kullanarak yapabiliyoruz. app in başındaki \ ile namespace in root tan başlamasını sağlıyorum.
Zaten Project.php nin namespace i olarak app tanımlı. (Tüm modellerde app tanımlı.)


Sınıfa erişirken tam yol tanımı verildi. Çünkü bir controller içerisindeyken zaten `namespace App\Http\Controllers;` 
tanımlaması yapıldığı için `App\Http\Controllers` namespace si altındasın. Buradan direkt 'app' yazarsan bulunduğu yerden itibaren arar.
Eğer uzunca yol tanımıyla uğraşmak istemezsen kullnacağın controler da `use App\Project;` diyerek o namespace i sayfaya ekleyebilirsin.
!! Burada neden app değilde App kullanıldı? !!

Eğer böyle bir tanımlama yapıldıysa artık `$projects = Project::all()` diyerek aynı sonuca ulaşılabilir.
Elde ettiğimiz kayıtlar controller içindeyken `return $projects;` ile dönebiliriz. Bu durumda ekrana Json formatında basılacaktır.
Bu json u chrome da pretty görmek için chrome json formatter eklentisini kullnabilirsin.

Bu json u view e göndermek için normal değişken gönderme yöntemi kullanılabilir. `return view('projects.index', ['projects' => $projects]);
`
Ayrıca `return view('projects.index', compact('projects'));` şeklinde de veriyi view e gönderebiliriz.
Değişkenleri compact komutuna string birer parametre olarak eklemeliyiz.
View leri controller lara göre gruplandırmak daha sonradan yönetilmeyi kolaylaştırır. 
Controller isminde klasör ve metot bir view olacak.
