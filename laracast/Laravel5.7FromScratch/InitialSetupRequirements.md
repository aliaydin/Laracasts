## Initial Setup Requirements

Laravel'i kurmak için laravel.com Documentation sayfasını gidip oradan Install Laravel bölümüne tıklanır.
Kurulumu yapabilmek için öncelikle Composer yüklenmelidir. (Composer ile ilgili ayrı bir dokuman yazılacak.)

`composer global require "laravel/installer"`

komutu ile laraveli indiriyorum. `global` diyerek her yerde geçerli bir paket olarak işaretliyorum.

composer ile inen kütüphaneler vendor klasörü altına iner. Biz global olarak indirdiğimiz için

`$home/.config/composer/vendor/bin`

altına attı.

`laravel new blog`

komutu ile yeni bir proje açılıyor.
Ben projeyi `/var/www/htm` altına oluşturdum.

Bu klasörde projeyi açtığımda log yazma hatası gelebilir. Bunu çözmek için

```
sudo chown -R $USER:www-data /var/www/html/blog

chmod -R 775 storage
```

komutlarını kullanmak gerekir.

`which composer`
komutu composer in global ya da local kurulduğunu öğrenmek için kullanılır. Gelen cevap

`/usr/local/bin/composer`

Ide için Atom kullanılablir. Atom IDE kurulumu:

`sudo add-apt-repository ppa:webupd8team/atom`
