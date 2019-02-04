## Form Delete Requests ##

Edit ekranındayken kaydı silmek için 2. bir form ve submit button kullandık. 

Bu sayede 2. bir endPoint e istek atabiliyoruz.

Delete formunu `DELETE` verb une cevap verecek olan `projects.destroy` rotasına yollamalıyız `/projects/{project}`

Aslında `edit` formu ve `delete` formu aynı `endpoint` e farklı `verb` lerle istek yapıyorlar.

`{{ method_field('DELETE') }}` ile gizli bir delete isteği gönderiyoruz.

`DELETE` isteğini `POST` ile gönderebileceğimiz için `a` tagı ile silme işlemi için ekstra .js kodu yazmak gerekiyor.

`{{ method_field('DELETE') }}` kodunu kısaca `@method('delete')` şeklinde yazabiliriz.

Ayrıca `{{ csrf_field() }}` kodunu da `@csrf` olarak yazabiliriz.

`destroy` metodu içerisine `Project::find($id)->delete();` kodunu ekleyerek istenilen kaydın silinmesini sağlayabiliriz.

Kayıtlara erişirken varolmayan bir id ile geldiğinde kod dağılmasın istiyorsak `find($id)̀` yerine `findOrFail($id)` kullanabiliriz.

Bu şekilde yapıldığında `404` sayfası gelir. Çünkü istenen `resourse` bulunamamıştır.

Eğer controllerlardan herhangi birisinde hata varsa `php artisan route:list` çalışmayacaktır.

