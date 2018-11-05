## Faking PATCH and DELETE Requests ##

Browser lar `PATH` ve `DELETE` Verb lerini tanımazlar. Bunları geçerli kılmak için Laravel de ufak bir trick kullanmak gerekir.

Formu gönderirken `POST` kullanıp forma gizli bir alan eklemeliyiz.

`{{ method_field('PATCH') }}` kodu sayesinde browser bir şey anlamasa da kodu karşılayan Laravel burada `PATCH` verb ünün gönderildiğini anlayacak ve ona göre işlem yapacak. Post edilen Form içerisindeki hidden alan `<input type="hidden" name="_method" value="PATCH">` kodu ile gider.

Form tasarımında css olarak bulmalink kütüphanesi kullanılabilir. Atom editoru için bulmacss paketini `apm install atom-bulma` komutuyla yükledim. bulmalink atom da nasıl kullanılır?

`Route::get('/projects/{project}/edit', 'ProjectsController@edit');` rotasındaki `{project}` bilgisine `edit` metodunda `$id` ile erişebiliriz.

`var_dump(); die;` işlemi için Laravel `dd()` metodunu eklemiş. Basit debug için kullanılabilir.

Edit lenen kayıtların formunun action metodunda `/projects/{{ $project->id }}` rotası bulunur. Bu rota bizi `update($id)` metoduna götürür.
Update metodu içerisinde önce `find()` ile kaydı buluruz. Daha sonra bu kaydı güncelleyip `save()` ile bilgileri DB ye kaydederiz.

```
$project = Project::find($id);

$project->title = request('title');
$project->description = request('description');

$project->save();
```
