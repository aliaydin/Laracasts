## Databases and Migrations ##

Rotaları `routes/web.php` dosyasında tanımlamalıyız. (Route Layer)

`php artisan make:controller controllerName` komutu ile `app/Http/Controllers` klasöründe controller oluştururuz. (Controller Layer)

JavaScript, Css ve View dosyalarımızı `resources` klasörü altına eklemeliyiz. (View Layer)
Aslında resources altına konan bu dosyalar minified ve compiled işlemlerinden sonra public altına alınır.
Ya da direkt public altına css, js eklenebilir.

Bu derste ise Model Layer hakkında bilgi sahibi olacağız. Laravel ORM olarak Eloquent kullanır.
Biz Model Layer da Eloquent Modelleriniz kullanacağız. Laravel Eloquent aslında bir ActiveRecord implementasyonudur.

`.env` dosyası tüm konfigurasyonun tutulduğu yerdir. Veritabanı için gerekli ayarlamaları `DB_CONNECTION` bölümünde yapacağız.
Proje paylaşıldıkğında .env karşı tarafa gitmez. Projeyi alan .env.example dan kendi ayarlarına uygun yeni bir .env oluşturur.
Ayrıca proje alındıktan sonra composer install çalıştırılmalıdır. Çünkü vendor klasörü .gitignore a eklidir.
Bir de her projenin kendine özel bir key bilgisi olacağı için clone lanan projeler için `php artisan key:generate` komutu çalıştırılmalıdır.
Bu kod ile app.php içerisindeki 'key' alanında kullanılacak bilgi .env dosyasına yazar.   

Mysql kurulmalı ve client olarak Mysql Workbench benzeri bir uygulama kurulmalıdır.
`tutorial` isminde bir DB oluşturulup bu DB ye erişim için gerekli ayarlar `.env` dosyasında yapılmalıdır.
Bizim `.env` dosyasında yaptığımız MySql ayarları `/config/database.php` dosyası tarafından kullanılır.

Bu dosya içerisindeki `'default' => env('DB_CONNECTION', 'mysql')` kodu default DB connection için
 DB_CONNECTION da tanımlı ayarları ya da mysql i (2. parametre) kullanmamızı söyler. 
 
Yine bu dosya içerisinde `connections` bölümünde tüm db driver ları için gerekli ayarlar bulunmaktadır. 
 Bu ayarlar da `.env` dosyasından okunur.

`php artisan migrate` komutu Laravel e özgü bir kavramdır. DB için versiyon kontrol sistemine benzer. Ayrıca ORM ile yakından ilişkilidir.
Bu yapı sayesinde sql komutları kullanmadan DB ye ilişkin tüm işlemleri yapabiliriz.

Komut çalıştırıldığında hata alınırsa php pdo eklentisi yüklenmemiş olabilir. Kontrol için `php -i | grep pdo_mysql` komutu kullanılabilir.

php-common olarak yüklendiyse (ubuntu da default öyle geliyor) hata alınması normaldir. Php yi yeniden modulleriyle yüklemek için;

```
sudo apt-get --purge remove php-common
sudo apt-get install php-common php-mysql php-cli
```

Yüklü modulleri kontrol için `php -m` komutu kullanılabilir.

Yükleme bittikten sonra tekrar `php artisan migrate` komutunu çalıştırdığımda

```
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
```

sonucunu aldım. Bu işlemin sonucunda tutorial DB si içerisinde 3 tablo oluştu.

`migrations` isimli tablo yapılan migration komutlarının logunu tutuyor. Diğer tablolar ise migration işlemi sonucu oluşan tablolar.
Yapılan bu işlemi geri almak için `php artisan migrate:rollback` komutu kullanılabilir.

Migration ile oluşturulan tablolar `database/migrations` altındaki dosyalardaki komutlar işletilerek oluşturulurlar.

Bu dosyalar `class CreateUsersTable extends Migration` Migration sınıfından türetilmiş sınıfları barındırırlar.
Bu sınıf içerisinde `public function up()` ve `public function down()` isimli 2 metot bulunur.

Up metodu tablo oluşturulurken kullanılır. Down metodu ise rollback işleminde çalıştırılır.

Tablolarda herhangi bir alanda değişiklik yapılmak isteniyorsa bu değişiklik direkt tablo üzerinde yapılmamalıdır. 
Bunun yerine buradaki class lar da değişiklik yapılmalı ve bu projeyi kullanan herkes yapılan 
bu değişiklikleri bu dosyalar üzerinden takip etmelidir.

Up metodu içerisinde `Schema::create('users', function (Blueprint $table) {` komutu ile users tablosu oluşturulur.
Metodun 2. parametresi ile anonim bir metot çalıştırılır ve bu metot içerisinde aşağıdaki gibi komutlar bulunur.
Eğer tabloda güncelleme yapılmak isteniyorsa `Schema::table` komutu kullanılmalıdır.  
php migrate ile komutuna parametre olarak verilen dosya ismi create_ ile başladığı için laravel `Schema::create` i otomatik ekler.

Her oluşturlan migrate dosyasında 

```
$table->increments('id');
$table->timestamps();
```
  
satırları hazır gelir. Bu satırları silmemek gerekir. İlk satır increments metodu ile auto_inc bir id alanı ekler.
Diğer satır genelde son satır olarak kullanılır ve tablodaki created_at ve updated_at alanlarını oluşturur.

```
$table->increments('id');
$table->string('name');
$table->string('email')->unique();
$table->timestamp('email_verified_at')->nullable();
```

 
Burada $table users tablosunu temsil eder.string var_char bir alan ekler. unique ile o sutünün unique olması sağlanır.

`php artisan migrate:fresh` komutu ile tüm tabloları kaldırıp yeniden oluşturabilirsin. Bu komut ancak local de çalıştırılmaldıır.
Refresh ve fresh arasında her zaman fresh tercih edilmelidir. refresh tamamen ignore edilebilir.

Laravel artisan da yeni dosya oluşturan komutların tamamı `php artisan make` ile başlar.
Yeni bir migration oluşturmak için `php artisan make:migration create_projects_table` komutu kullanılmalıdır.

Buradaki create ile oluşturma işlemi yapılacağı söylenir. Bu işlem sonucu projects tablosu oluşturulur.
Biz komutu çalıştırdığımızda bize `Created Migration: 2018_11_02_134604_create_projects_table` sonucu döner.
Genelde migration lar make:migration la oluşturulmak yerine -m ile make:model in parametresinde oluşturulurlar.
 

Biz migrate dosyasını komutla oluşturduğumuz için boilerplate hazır gelir.

Artisan da komutlarla ilgili yardım almak için `php artisan help komutAdi` kullanımı bulunmaktadır.

Bize hazır gelen id ve timestamps alanlarını silmemek en güzelidir. Biz de tabloya

```
$table->string('title');
$table->text('description');
```

kısmını ekledik.

Biz `php artisan migrate:rollback` ile en son yapılan migrate işlemini geri alıyoruz. Öncekilere dokunmuyor.

`php artisan migrate:rollback --step=1` --step parametresiyle kaç rollback geri alınmak isteniyorsa o kadar geriye gidilebilir.

Batch olarak çalıştırılan tablolar aynı batch numarasına sahip olurlar ve tek step te eklenip kaldırılırlar.

Hatta down metodunun içini boşaltırsak hiçbir işlem yapmıyor. Çünkü rollback down metodunu çalıştırıyor.

Biz kaldırma işlemini garantiye almak istersek `php artisan migrate:fresh` komutunu kullanmalıyız.

Bu komut tüm tabloları kaldırıp sonra tüm migration sınıflarını tekrar çalıştırır. Down metodu ile uğraşmıyor.