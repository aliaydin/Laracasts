## Sending Data to Your Views ##

Controller dan View e veri göndermek için View metoduna 2. parametre verilmelidir.
Bu parametre bir dizidir ve hazırladığımız veriler bu dizi üzerinden view e gönderilir.

`return view('welcome', ['tasks' => $tasks]);`

Kodu ile welcome view dosyası tasks değişkeni ile yükleniyor.
Bu değişkeni viewde aşağıdaki gibi kullanabiliriz.

```
<?php foreach ($tasks as $task) { ?>
  <li><?= $task; ?> </li>
<?php } ?>
```
View de bu şekilde php kullanıldığında çirkin görünüyor. Blade gibi templete engine ler de bu yüzden oluşturuldu.
Blade kullanmak için @ işareti (Razorda da aynısı var.) kullanılır.

Bu kodun Blade ile yazılmış versiyonu:

```
@foreach($tasks as $task)
  <li> {{ $task }} </li>
@endforeach
```
foreach {} yerine foreach endforeach kullanımı okunabilirliği arttırmak için sonradan eklendi.

Sayfa derlendiğinde Blade @ kullanılan kısımları <?php yi çevirir.
Yapılan bu işlem cache te tutulur ve 2. istekte daha hızlı cevap verilir.

Controllerdan sadece kendi tanımladığımız değişkenleri değil queryString ile gelen verileri de gönderebiliriz.
title değişkenine ?title ile gelen bilgiyi alıp view e gönderiyoruz.

En yaygın kullanım:

```
return view('welcome', [
  'tasks' => $tasks,
  'foo' => 'foobar',
  'title' => Request('title')

]);
```
Echo için `<?= short_tag` yerine `{{ }}` kullanılır. Fakat `{{ }}` sadece echo yapmaz.
Aynı zamanda gelen veriyi escape eder.

`'hack' => '<script>alert("Hacked");</script>'`

gibi zararlı bir kod geldiğinde bu kodu `&lt;script&gt;alert(&quot;Hacked&quot;);&lt;/script&gt;` haline çevirir.

Gelen verinin escape edilmesini istemezsem `{!! !!}` şeklinde kullanırım, ama tercih edilmemelidir.
Sonuçta gelen veriye güvenilmemelidir. Kullanıcıdan gelen her bilgi suçsuz olduğu ıspatlanıncaya kadar suçludur.

Ayrıca veri gönderirken

`return view('welcome')->withTasks($tasks)->withFoo('foo');`

şeklinde bir kullanım da var. Bu kullanımda Laravel withTasks kısmındaki Tasks kısmını alıyor ve bir değişken olarak View e parametre olarak veriyor. Bu kullanım genelde 2. parametreye tek değişken gönderileceği zaman kullanışlıdır.
Burada withTasks diye bir metot aslında yok. Burada Laravel Magic method kullanarak bu metodu çeviriyor.

Hatta aynı kodu 2. parametre kullanmak yerine with metoduna parametre olarak ta verebiliriz.

```
return view('welcome')->with([
  'tasks' => $tasks,
  'foo' => 'foobar',
  'title' => Request('title'),
  'hack' => '<script>alert("Hacked");</script>']
```

Yukarıdaki 3 kullanım arasında en yaygın olanı 1. kullanımdır. Bu kullanım codeIgniter syntax ına da yakın.
