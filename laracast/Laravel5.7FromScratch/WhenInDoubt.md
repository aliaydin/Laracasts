## When in Doubt ##

Bir önceki konuda yapılan `refactoring` le kod daha okunabilir hale geldi. Ayrıca Task sınıfı kendine ait alanları kendine ait metotları kullanarak düzenliyor. Fakat yine de Controller daki

`$method = request()->has('completed') ? 'complete' : 'incomplete';`

kodu biraz karışık duruyor. Burada completed değişkenini formdan almak yerien formu yeni bir controller oluşturup oraya yönlendirsek daha güzel olur.

`php artisan make:controller CompletedTasksController` kodu ile yeni controller oluşturduk.

Rest mantığına göre `store` ve `destroy` metotlarına `POST` ve `DELETE` isteklerini yönlendirmeliyiz.

```
public function store(Task $task) {
    $task->complete();

    return back();
}

public function destroy(Task $task) {
    $task->incomplete();

    return back();
}
```

Bu kodlar sayesinde artık ProjectTasksController ı içerisinde gelen requesti if le kontrol etmek yerine 2 farklı metoda istek yapabiliriz.

`web.php` den `Route::patch('/tasks/{task}', 'ProjectTasksController@update');` rotasını kaldırıp

```
Route::post('/completed-tasks/{task}'. 'CompletedTasksController@store');
Route::delete('/completed-tasks/{task}'. 'CompletedTasksController@destroy');
```

rotalarını ekledim.

Artık `show.blade.php` ye gidip task complete islemini yapan formun action bilgisini güncellemeyeliyiz. Bir de orada önceden PATCH yaptığımız için `@method('PATCH')` kodunu kullanıyorduk. Ama artık gerek yok.

`CompletedTasksController` da `Task` modelini kullandığımız için `use App\Task;` ekliyoruz.

Formun 2 farklı actiona gidebilmesi için kontrol lazım.

```
@if ($task->completed)
    @method('DELETE')
@endif
```

Task işaretliyse `DELETE` değilse `POST` metotları çağrılacak.

Kodun karmaşıklığı konusunda şüpheye düşüldüğünde yeni bir controller oluşturulabilir. Burada sadece 1 checkbox ın state ini düzenlemek için bir controller oluşturuldu.

Buradaki yaklaşımlardan her ikisi de doğru. Biri diğerini üstün değil. 
