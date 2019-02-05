## Two Layers Of Validation ##

Formu kaydetmek istediğimizde alanları boş bıraksak dahi form submit edilebiliyor.
 
Ve `SQL` hatasına neden oluyor. Gerekli alanları boş geçilmediğinden emin olmak gerekiyor.

Bunun için öncelikle `Client Side Validation` yapmak gerekiyor. 
Zorunlu alanlara `required` yazarak bu alanların boş geçilememesini sağlayabiliyoruz. 
Tabi uygulamanın güvenliğini sadece client ta tutmak doğru değil. 
Bir kullanıcı `F12` ile gelip o `required` parametresini kaldırarak yine de bu alanı boş geçebilir. 
Bu yüzde backend te de bir şeyler yapmak gerekiyor.

`create` formu gönderildiğinde `store` metodu çalışıyor. Validation burada yapılmalı.

`request()` `helper` ının `validate` özelliği de var. Bu özelliği kullanarak alanları doğrulayabiliriz.

`validate()` metodu `['fieldName' => 'validationRules', ..]` şeklinde bir diziyi parametre olarak alır.  
 
`request() helper` ı aslında geriye bir `nesne` döner ve biz bu nesnenin `validate` metodunu kullanırız.

```
request()->validate([
  'title' => ['required', 'min:3'],
  'description' => 'required'
]);
```

Bu şekilde kullandığımızda eğer FrontEnd korumayı atlatırsa buradan geri dönecektir. 
Yolladığımız form işlenmeyecek ve alanlardaki bilgiler kaybolmuş bir şekilde form yeniden açılacaktır. 
Çünkü arka planda validate olmadığı için redirect edilecek. Bu pratik bir durum değil!

Aslında redirect işlemi yapılırken oluşan hatalarda yine redirect edilen sayfaya dönülüyor. 
Geri dönen hatalar `$errors` nesnesinde tutuluyor. 
Burada hiç hata olmasa dahi bu nesne yine var fakat boş. Biz hatalara `$errors->all()` ile erişiyoruz.

Hata var mı diye bakmak için `$errors->any()` ve hataları listelemek için `@foreach($errors->all() as $error)`

`@if ($errors->any())` ile hata varsa notification çıkmasını sağlayabiliriz. 
Ayrıca `class="{{ ($errors->has('title')) ? 'is-danger' : '' }}` ile hata olan inputlara class verebiliriz.
Burada `is-danger` `bulma.css` için `bootstrap` için `has-error` olabilir.
`$error` hata olmasa bile yükleneceği için önceden bir kontrol yapmaya gerek yok. Direkt kullanılabilir. 


```
@if ($errors->any())
  <div class="notification is-danger">
    <ul>
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
```        

`required` ile sadece bu alanı zorunlu hale getiriyorduk. Ama istersek başka validasyonlar da yapabiliriz. 
codeIgniter gibi diğer parametreleri `|` ile ayırabiliriz ya da tümünü `[]` dizi içerisinde verebiliriz.

Mesele en az 3 karakter girilmesi isteniyorsa `min:3` eklenebilir. Ya da `max:50` eklenebilir.

Son olarak sayfa redirect edildiğinde bilgilerin de gelmesi sağlanabilir. Bunun için `old()` helper ı kullanılır.

`create` formu için sorun değil ama `edit` formunda validasyon sonrası Modelden gelen ya da validasyondan gelen veri ayrılmalı. 

Laravel da çok çok fazla validation rule var. Bunları ihtiyaç oldukta manual den bakıp uygulayabilirsin.

Mesela parola doğrulamak için ilk alan `foo` ise 2. alanın ismi `foo_confirmation` olmalıdır. 
Yani `"_confirmation"` eklenmelidir.

Biz `store` içinde önce validation için title ve description alanlarını kullandık. 
Daha sonra bunları `Create` metoduna gönderdik. Aynı aynı alanları 2 kez gönderdik. Bakımı zor.
2 alan ile uğraştığında tekrar can yakmaz ama alan sayısı arttığında hata ihtimali artabilir. DRY gereği tekrar yapmamak lazım.  
Bunu engellemek için validate metodunun işini bitirdikten sonra bize alanları dizi olarak dönmesinden faydalanabiliriz.

```
$validated = request()->validate([
  'title' => ['required', 'min:3'],
  'description' => 'required'
]);

Project::create($validated);
```

create metodunun içerisinde direkt `request()->valdate()` verilse yine çalışır. 
Çünkü eğer doğrulama olmazsa `validate` içerisinde direkt yönlendiğimiz için `create` metodu asla çalışmaz. 
Ama bu kullanım `cleanCode` prensiplerine uygun değil.
