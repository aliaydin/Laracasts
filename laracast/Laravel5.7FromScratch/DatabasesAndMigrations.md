## Databases and Migrations ##
Rotaları `routes/web.php` dosyasında tanımlamalıyız. (Route Layer)
Controller ları `app/Http/Controllers` klasöründe ya da kod la `php artisan make:controller controllerName` oluşturmalıyız. (Controller Layer)
JavaScript, Css ve View dosyalarımızı `resources` klasörü altına eklemeliyiz. (View Layer)
Bu derste ise Model Layer hakkında bilgi sahibi olacağız. Laravel ORM olarak Eloquent kullanır.
Biz Model Layer da Eloquent Modelleriniz kullanacağız. Bu işlem için ActiveRecord patterni kullanılır.
`.env` dosyası tüm konfigurasyonun tutulduğu yerdir. Veritabanı için gerekli ayarlamaları `DB_CONNECTION` bölümünde yapacağız.
Mysql kurulmalı ve client olarak Mysql Workbench benzeri bir uygulama kurulmalıdır.
`tutorial` isminde bir DB oluşturulup bu DB ye erişim için gerekli ayarlar `.env` dosyasında yapılmalıdır.
Bizim `.env` dosyasında yaptığımız MySql ayarları `/config/database.php` dosyası tarafından kullanılır.
Bu dosya içerisindeki `'default' => env('DB_CONNECTION', 'mysql')` kodu default DB connection için DB_CONNECTION da tanımlı ayarları ya da mysql i (2. parametre) kullanmamızı söyler. Yine bu dosya içerisinde `connections` bölümünde tüm db driver ları için gerekli ayarlar bulunmaktadır. Yine bu driver lar da ayarları `.env` dosyasından okur.
`php artisan migrate` komutu Laravel e özgü bir kavramdır. DB için versiyon kontrol sistemine benzer. Ayrıca ORM ile yakından ilişkilidir.
Bu yapı sayesinde sql komutları kullanmadan DB ye ilişkin tüm işlemleri yapabiliriz.

Komutu çalıştırdığımda hata aldım. Php yüklü fakat pdo_driver yüklü değil. Kontrol için `php -i | grep pdo_mysql` komutunu kullandım.

Hata almamım nedeni php nin common olarak yüklenmiş olmasıydı. Php yi yeniden modulleriyle yüklemek için;

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
`migrations` isimli tablo yapılan migration komutlarının logunu tutuyor. Diğer tablolar ise migrate için tanımlı tablolar.
Yapılan bu işlemi geri almak için `php artisan migrate:rollback` komutu kullanılabilir.
Migration ile oluşturulan tablolar `database/migrations` altındaki dosyalardaki komutlar işletilerek oluşturulurlar.

Bu dosyalar `class CreateUsersTable extends Migration` Migration sınıfından türetilmiş sınıfları barındırırlar.
Bu sınıf içerisinde `public function up()` ve `public function down()` isimli 2 metot bulunur. Up metodu tablo oluşturulurken kullanılır.
Down metodu ise rollback işleminde çalıştırılır.

Tablolarda herhangi bir alanda değişiklik yapılmak isteniyorsa bu değişiklik direkt tablo üzerinde yapılmamalıdır. Bunun yerine buradaki class lar da değişiklik yapılmalı ve bu projeyi kullanan herkes yapılan bu değişiklikleri bu dosyalar üzerinden takip etmelidir.

Up metodu içerisinde `Schema::create('users', function (Blueprint $table) {` komutu ile users tablosu oluşturulur.
Metodun 2. parametresi ile anonim bir metot çalıştırılır ve bu metot içerisinde aşağıdaki gibi komutlar bulunur.
```
$table->increments('id');
$table->string('name');
$table->string('email')->unique();
$table->timestamp('email_verified_at')->nullable();
```
Burada $table users tablosunu temsil eder. increments metodu auto_inc bir alan ekler. string var_char bir alan ekler.
unique ile o sutünün unique olması sağlanır.

`php artisan migrate:fresh` komutu ile tüm tabloları kaldırıp yeniden oluşturabilirsin. Bu komut ancak local de çalıştırılmaldıır.

Laravel artisan da yeni dosya oluşturan komutların tamamı `php artisan make` ile başlar.
Yeni bir migration oluşturmak için `php artisan make:migration create_projects_table` komutu kullanılmalıdır.
Buradaki create ile oluşturma işlemi yapılacağı söylenir. Bu işlem sonucu projects tablosu oluşturulur.
Biz komutu çalıştırdığımızda bize `Created Migration: 2018_11_02_134604_create_projects_table` sonucu döner.
Biz migrate dosyasını komutla oluşturduğumuz için boilerplate hazır gelir.
Artisan da komutlarla ilgili yardım almak için `php artisan help komutAdi` kullanımı bulunmaktadır.
Bize hazır gelen id ve timestamps alanlarını silmemek en güzelidir. Biz de tabloya

```
$table->string('title');
$table->text('description');
```
kısmını ekledik.
Biz `php artisan migrate:rollback` ile en son yapılan migrate işlemini geri alıyoruz. Öncekilere dokunmuyor.
Hatta down metodunun içini boşaltırsak hiçbir işlem yapmıyor. Çünkü rollback down metodunu çalıştırıyor.
Biz kaldırma işlemini garantiye almak istersek `php artisan migrate:fresh` komutunu kullanmalıyız.
Bu komut tüm tabloları kaldırıp sonra tüm migration sınıflarını tekrar çalıştırır.
