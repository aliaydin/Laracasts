## You May Only View Your Projects ##


`Core Concepts` derslerinden sonra projeye kaldığımız yerden devam ediyoruz.

Biz projeleri gösterirken `$projects = Project::all();` komutunu kullandık. Bu komut `DB` de ne varsa getirdi.

`all()` ile tüm projelerin çekilmesi ancak bir tutorial için normal olabilir. 
Gerçek projelerde her kullanıcıya kendine ait projeleri göstermek gerekir.

Bunun için `Project::all` yerine `Project::where('owner_id', auth()->id())->get()` kodu eklenmelidir.

Biz Projeler içerisinde `owner_id` si login olmuş `kullanıcı id` sine eşit olanları istiyoruz.
`Eloquent` bu komutu 

`select * from projects where owner_id = 4` 

gibi bir koda dönüştürüyor. (4 dummy kullanıcı id)

`auth()` helper metodu `\Auth` sınıfını temsil eder. Eğer `Auth` gibi kullanılacaksa `use Auth;` ile eklenmeli. Helper daha iyi.

`auth()->id()` ile login olmuş kullanıcı bilgisine erişilebilir. Eğer guest ise null dönecektir.

`auth()->user()` ise bize login olmuş user instance sini döner.

`auth()->check()` boolean döner. Eğer kullanıcı login ise true, değilse false döner.

`auth()->guest()` ise `check()` metodunun tersidir. Login olunmadıysa true döner.

Biz proje tablosunu tasarlardan `owner_id` yi düşünmedik. Bu yüzden komut hata verecektir.

`create_projects_table.php` ye gidip `owner_id` field ini de eklemek gerekiyor.

Production ortamında olmadığımız için veriyi create e ekleyip `migrate:fresh` dememiz yeterli.
Ama eğer production da olsaydık kesinlikle bu komutu çalıştıramazdık. (Console Uyarı veriyor zaten.)

`$table->unsignedInteger('owner_id');` ile gerekli alan eklendi. 
`unsignedInteger` kodu pozitif bir değer girilmesini garanti altına alır. 
`owner_id` başka bir tablodan id olarak gelecek. 0 ya da eksi olması mümkün değil.
Burada `owner_id` yerine `user_id` de kullanılabilir. Ama projenin sahibi olan kullanıcı anlamında owner_id daha mantıklı.

`$table->foreign('owner_id')->referances('id')->on('users')->onDelete('cascade');`

Ayrıca kodun sonuna yukarıdaki kodu da ekledim. 
Bu tablodaki owner_id alanı aslında `foreign` bir alandır ve users tablosundaki id alanına karşılık gelir.
Ayrıca user tablosundaki karşılığı silindiği zaman buradaki bu user a ait veriler de silinmelidir.

`onDelete('cascade')` kodu her zaman detail de bulunur. 1->n de ki n de durmalıdır. 1 gittiğinde o da gitsin diye.
Ayrıca buradaki silme işlemi PHP ile manuel de yapılabilir. Ama buradaki yaklaşım daha doğru.

project tablosunun yapısına sql client ile baktığımızda `projects_owner_id_foreign` ile owner_id ye bir index tanımlandığı görülür.

Auth mekanizmasını daha önceden sqlite için kurmuştum. Yine aynı komutla bu proje için de kurulum yaptım.

`projects/create` rotasına sadece yetkisi olan kullanıcılar girebilmelidir. Bunun için middleware leri kullanacağım.

Middleware i eklemek için 2 farklı yöntem var. Ya kullanılacak sınıfın `__consturt()` metoduna eklenir, ya da rotaya.
ProjectController da 

```
public function __construct()
    {
        $this->middleware('auth');
    }
```
 
kodu ile kurucuya middleware eklendi. 

Eğer middleware controller da konacaksa hangi metotlarda gerekli olduğuna karar verilmelidir.
Bazı actionlarda geçerli olmasını istersek `only` metodu ile actionları tanımlamalıyız.

`$this->middleware('auth')->only(['store', 'destroy']);` 

Bazıları hariç hepsinde geçerli olsun demek için `except` metodu kullanılmalıdır.  

`$this->middleware('auth')->except(['show']);`

`Project/create` sayfasına yetkili bir kullanıcı ile erişip proje eklemek istediğimizde hata alacağız.
Çünkü `owner_id` alanına veri göndermedik. 

Buraya veri göndermek için `store()` metoduna 

`$validated['owner_id'] = auth()->id();` 

kodunu eklemek yeterlidir. `$validated` dizisi bizim validasyondan geçmiş verilerimizi temsil ediyor.

Diziye veri ekleme işlemi 

`Project::create($validated + ['owner_id' => auth()->id()])` 

şeklinde de yapılabilirdi.

Diğer bir yol ise `eloquent` kullanmak fakat bunu daha sonra göreceğiz.

Artık `Authentication` yapısı güzel çalışıyor ve kullanıcı tablodaki tüm verileri değil de kendine ait olanları görebiliyor.

Fakat `Authorization` yapısı eklemediğimiz için 1. kullanıcıya ait bir kaydı 2. kullanıcı da editleyebiliyor.

`/projects/1` dediğimizde 1. kullanıcıya ait veriyi 2. kullanıcıdan login olsak bile görebiliyoruz.

Bunun sebebi bizim sadece `Authentication` u kontrol etmemiz, `Authorization` a bakmamamızdır.

__Authentication:__ login + password (who you are)
   
__Authorization:__ permissions (what you are allowed to do)
