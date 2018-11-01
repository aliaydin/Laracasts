
Laraveli kurmak için laravel.com Documentation sayfasını gidip. oradan install Laravel bölümüne tıklanır.
Laraveli kurabilmek için Composer kullanılır.

`composer global require "laravel/installer"`

komutu ile laraveli indiriyorum. global diyerek her yerde geçerli bir paket olarak işaretliyorum.
composer ile inen kütüphaneler vendor klasörü altına iner. Biz global olarak indirdiğimiz için

`$home/.config/composer/vendor/bin`

altına attı.
laravel new blog ile yeni proje açılıyor.
Ben projeyi /var/www/htm altına oluşturdum.

Bu klasörde projeyi açtığımda log yazamadığını gördüm. Yetki hatası vardı gidermek için aşağıdaki kodları kullandım.

```
sudo chown -R $USER:www-data /var/www/html/blog

chmod -R 775 storage
```

`which composer`
komutu composer in nasıl kurulduğunu anlamak için kullanılır. Gelen cevap

`/usr/local/bin/composer`

Ide için Atom lazım oldu. Atom IDE kurulumu:

`sudo add-apt-repository ppa:webupd8team/atom`
