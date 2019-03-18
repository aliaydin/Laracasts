## Authorization Essentials ##

Proje sayfasında bir kullanıcı diğer bir kullanıcıya ait projeyi editleyebiliyor, hatta task bile girebiliyor.

Bu sorunu çözmenin birden fazla yolu var.

İlk metot, ProjectsController a gelip show metodu içerisine bu projenin owner_id si login olan kullanıcı id si değilse, çık diyebiliriz.

```
if ($project->owner_id != auth()->id()) {
    abort(403);
}
```

Yukarıdaki kodla birlikte deneme2 kullanıcısı deneme1 kullanıcısına ait kayda bakmak istediğinde 403 hatasını alacaktır.

Bu kodu abort_if helper fonksiyonuyle tek satıra da düşürebiliriz. 1. parametre doğru ise 2. status koduyla çıkış yap.

`abort_if($project->owner_id != auth()->id(), 403);`

Eğer abort_if yerine abort_unless helper ı kullanılırsa `$project->owner_id == auth()->id()` koşul tersine de çevrilebilir.

OOP yaklaşımı kullanmak gerekirse `app/User` sınıfına owns() metodu eklenebilir. O zaman koşulu bu metot içerisine alabiliriz.

OOP kullanmak burada okumayı da kolaylaştırır.

İkinci bir metot ise Policy tanımlamaktır. 

`php artisan make:policy ProjectPolicy` kodu ile Project modeline policy tanımlanabilir. Convention ModelNameProlicy şeklinde.

Bu komut `app\Policies` klasörünü oluşturup içerisine bizim tanımladığımız policy dosyasını ekledi.

Aynı komutu `--model=Project` parametresi ile çağırdığımızda (Project Modeli için policy oluştur) bizim için hazır kodları verecektir.
Hazır gelen bir metotlardan işimize yaramayanları silip, diğerlerini kullanabiliriz.

Genelde `view` metodu tek başına yetiyor. `public function view(User $user, Project $project)`

Metoda parametre olarak verilen User bazen gelmeyecekse `?User $user` şeklinde kullanılmalıdır.
`$user` o anki aktif kullanıcıyı temsil eder.

2 parametre var ve bu kullanıcı bu proje için yetkili mi diye bakmak gerek.

Metot içerisine `return $project->owner_id == $user->id;` kodunu ekledim. 

Policy tanımlamak tek başına yeterli değil. Bunu register etmek gerekir.
`Providers/AuthServiceProvideer.php` içerisinde 

```
protected $policies = [
    'App\Model' => 'App\Policies\ModelPolicy',
]; 
```

dizisi tanımlı. Burada örnek olsun diye ilk elemanı da koymuşlar. Bunu `'App\Project' => 'App\Policies\ProjectPolicy',` olarak düzenledim.

Bu sayede her eloquent modelinin hangi policy ile tanımlı olduğunu artık biliyoruz.

Register işleminden sonra artık bu policy yi istediğimiz yerlerde kullanabilmek için kod yazmamız gerekiyor.

Tekrar ProjectsController daki show metoduna gelip `$this->authorize('view', $project);` kodunu ekledim.
`authorize` metodu 1. parametredeki action ın 2. parametredeki resource için mümkün olup olmadığına bakıyor.
Yani aslında bizim ProjectPolicy de tanımladığımız view metodunu $project ile çağırıyor.
İşlem sonrası gerekli status code ile sayfa yönlendirmesini otomatik yapıyor.

Aslında bu kontrol show, edit, update gibi tüm metotlar için aynı şekilde yapılmalı. 
Bu yüzden policy deki diğer metotları silip view deki kodu update metoduna taşıdık. 
`authorize` metodu ile çağırırken artık 1. parametreye `'update'` dememiz gerekiyor.
update metodunun ilk parametresi olan User $user Laravel tarafından sağlanıyor. Eğer guest ihtimali de varsa ?User olarak kullan.

Üçüncü bir yöntem olarak Laravel in Gate facade ı kullanılabilir. \Gate::allows ya da \Gate::denies metotları yeterli.

`abort_if (\Gate::denies('update', $project), 403);` 

Burada Gate i uygulamaya girişin yapıldığı büyük bir kapı gibi düşünebiliriz. Eğer Gate $project için update metodunu denies ediyorsa abort et.

`abort_unless(\Gate::allows('update', $project), 403);` 

Burada hem denies kısmı allows oldu hem de if kısmı unless oldu. 2 kere terslenip aynı sonucu verdi.
Gate $project için update izni vermediği sürece 403 dön. Aşağıdaki şekilde cümle yazar gibi kod yazabiliyorsun.

`Abourt unless our Gates allows an update of the project.`

Dördüncü yöntem olarak routes içerisinde middleware ile de yapılabilir.

`Route::resource('projects', 'ProjectsController')->middleware('can:update,project');`

Yukarıdaki can middleware i project için update yapabilirse girişe izin vermektedir.
`'can:update,project'` ifadesinde boşluk kullanılmamalıdır. Boşluk olunca görmüyor.

Buradaki project ifadesi route:list te gösterilen parametre ismidir. {project} e karşılık gelmektedir.
Zaten ProjectPolicy deki update metoduna Project $project olarak parametre geçmiştik.
Eğer polict middleware de tanımlandıysa php artisan Route:list ile Middleware kısmında tanımlamamızı görebiliriz.

Ayrıca policy blade dosyalarında dahi kullanılabilir.

```
@can('update', $project)
    Update Linki burada olmalı.
@endcan
```

Bu kullanım kullanıcının sayfayı görme yetkisi var ama update yetkisi yoksa faydalı olur.

Beşinci ve son metot 

`auth()->user()->can('update', $project)` kullanımıdır. Aslında bu kullanım aynı API ye farklı bir metot ile erişimdir.

 

Burada çok fazla yöntem var. Tümü bilinmek zorunda değil. Sadece 1 tanesini seç ve onunla yola devam et.
Best Practice 2. yöntem. 

Bazı durumlarda superUser kullanıcıları da olabilir. Bu durumda onlar başka kullanıcıların projelerine girebilmelidirler.

Basit çözüm olarak kontrol yapılan yere || $user->id = 5; gibi hardcoded superUser id si verilerek geçiştirilebilir.

Bunun yerine Providers/AuthServiceProvider.php nin boot metoduna before hook u eklenebilir.
Bu sayede policy çalışmadan evvel before hook devreye girer ve oradaki kodlar çalışır.

```
public function boot(Gate $gate)
{
    $this->registerPolicies();

    $gate->before(function ($user) {
        return $user->isAdmin();
    });

}
```

Gate facade i zaten use edilmiş onu boot metoduna inject ettim. Daha sonra $gate için before hook u nu oluşturdum.

$user Gate e gelen kullanıcı bilgisi. $user->isAdmin() ise User modelinde tanımlayabileceğimiz bir metot.

Eğer admin yetkilerinde bir kullanıcı ise true dönecektir ve metot true döndüğü için diğer kontrolu gerek kalmayacaktır.

Basitçe burada return $user->id == 2 ile 2. kullanıcının admin olduğu durum geçiştirilebilir.

Yukarıdaki kodda bir hata var. return $user->isAdmin(); kodu admin olan kullanıcı için true döner olmayan için ise false.

Eğer before hook undan bir sonuç dönerse bir sonraki yere geçmez. Bu yüzden bu kod

`if ($user->isAdmin()) return true;` 

olarak düzenlenmelidir. Aksi halde sonuç dönmeyeceği için kod devam edecek ve diğer kontroller çalışacaktır.

Gate i metoda parametre geçip nesne üzerinden static metodu çağırmak istediğimde hata aldım.

Bunun yerine `Gate::before(function ($user)` 