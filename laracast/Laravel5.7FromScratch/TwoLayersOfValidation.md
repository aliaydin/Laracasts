## Two Layers Of Validation ##

Formu kaydetmek istediğimizde alanları boş bıraksak dahi form submit ediliyor. Ve SQL hatası alıyorum. Alanların girildiğinden emin olmam gerekiyor.

Bunun için öncelikle Client Side Validation yapmak gerekiyor. Önce javaScript ile bu kontrolleri yaparken artık zorunlu alanlara required yazarak bu alanların boş geçilememesini sağlayabiliyoruz. Tabi uygulamanın güvenliğini sadece client ta tutmak doğru değil. F12 ile gelip o yazıyı oradan kaldırarak yine de bu alanı boş geçebilirler. Bu yüzde backend te de bir şeyler yapmak gerekiyor.

Formu gönderdiğimizde `store` metodu çalışıyor. Validation burada yapılmalı. `request()` metodu bize nesne döner. Bu nesnenin validate metodunu kullanabiliriz.

```
request()->validate([
  'title' => ['required', 'min:3'],
  'description' => 'required'
]);
```

Bu şekilde kullandığımızda eğer FrontEnd korumayı atlatırsa buradan geri dönecektir. Yolladığımız form işlenmeyecek ve alanlardaki bilgiler kaybolmuş bir şekilde form yeniden açılacaktır. Çünkü arka planda validate olmadığı için redirect edilecek. Bu pratik bir durum değil!

Aslında redirect işlemi yapılırken oluşan hatalarda yine redirect edilen sayfaya dönülüyor. Geri dönen hatalar `$errors` nesnesinde tutuluyor. Burada hiç hata olmasa dahi bu nesne yine var fakat boş. Biz hatalara `$errors->all()` ile erişiyoruz.

Hata var mı diye bakmak için `$errors->any()` ve hataları listelemek için `@foreach($errors->all() as $error)`

`@if ($errors->any())` ile hata varsa notification çıkmasını sağlayabiliriz. Ayrıca `class="{{ ($errors->has('title')) ? 'is-danger' : '' }}` ile hata olan inputlara class verebiliriz.

required ile sadece bu alanı zorunlu hale getiriyorduk. Ama istersek başka validasyonlar da yapabiliriz. codeIgniter gibi diğer parametreleri `|` ile ayırabiliriz ya da tümünü `[]` dizi içerisinde verebiliriz.

Mesele en az 3 karakter girilmesi isteniyorsa `min:3` eklenebilir. Ya da `max:50` eklenebilir.

Son olarak sayfa redirect edildiğinde bilgilerin de gelmesi sağlanabilir. Bunun için `old()` helper ı kullanılır.

Laravel da çok çok fazla validation rule var. Bunları ihtiyaç oldukta manual den bakıp uygulayabilirsin.

Mesela parola doğrulamak için ilk alan `foo` ise 2. alanın ismi `foo_confirmation` olmalıdır. _"_confirmation"_ eklenmelidir.

Biz `store` içinde önce validation için title ve description alanlarını kullandık. Daha sonra bunları `Create` metoduna gönderdik. 2 alan ile uğraştığında tekrar can yakmaz ama alan sayısı arttığında hata ihtimali artabilir. DRY gereği tekrar yapmamak lazım. Bunu engellemek için validate metodunun işini bitirdikten sonra bize alanları dizi olarak dönmesinden faydalanabiliriz.

```
$validated = request()->validate([
  'title' => ['required', 'min:3'],
  'description' => 'required'
]);

Project::create($validated);
```

create metodunun içerisinde direkt `request()->valdate()` verilse yine çalışır. Çünkü eğer doğrulama olmazsa validate içerisinde direkt yönlendiğimiz için create metodu asla çalışmaz. Ama bu yazım clean olmadığı için tercih etmiyorum.