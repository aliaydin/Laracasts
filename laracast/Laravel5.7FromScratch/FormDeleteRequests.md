## Form Delete Requests ##

Edit ekranındayken kaydı silmek için 2. bir form kullandık. Bu sayede 2. bir endPoint e istek atabiliyoruz.
`{{ method_field('DELETE') }}` ile gizli bir delete isteği gönderiyoruz.

`{{ method_field('DELETE') }}` kodunu kısaca `@method('delete')` ve `{{ csrf_field() }}` kodunu da `@csrf` olarak yazabiliriz.

Kayıt düzenlemeye olmayan bir id ile geldiğinde kod dağılmasın istiyorsak `find($id)̀` yerine `findOrFail($id)` kullanabiliriz.

Bu şekilde yapıldığında `404` sayfası gelir. Çünkü istenen `resourse` bulunamamıştır.
