

Video4: Blade Layout Files
about sayfası ekleyip oraya bir navigasyon ekledik. Daha sonra bu menu yu diğerlerine ekledik. Bu menüdeki her değişiklik için tüm sayfalarla uğraştık.
Bize PartialInclude imkanı verecek bir yapı lazım. Laravel de buna Layout deniyor. .net mvc de de benzer bir bir yapı vardı.
Blade: kodlar php de derlendikten sonra sayfayı render eden bir yapıdır. Biz Layout içerisine sayfanın yapısını kodlayacağız.
Dinamik yerler için @yield('content') gibi bir bölüm ekledik.
Layout dosyasını kullanacağımız dosyanın başına @extends('layout') demek yeterli. Eğer klasör içerisinde bir yapı varsa 'dirName.fileName' olarak çağrılır.
Layout dosyaları için root /resources/views klasörüdür.
@section('content') .. @endsection: Bu kısımlara yazacağımız her şey layout dosyasındaki content ile işaretlenmiş yere eklenecektir.
Her sayfa için ayrı title koymak için @section('title') kullanılabilir. Hatta @yield('title', 'Default Title') ile varsayılan title da verilebilir.
Aynı şekilde section kullanılırken de 2. parametre ile değer verilebilir. @seciton('title', 'contact') gibi..
Yukarıdaki kullanımda @endsection kullanılmaz. Zaten herşey fonksiyona verilen parametre ile tamamlanmaktadır. Blok açılmadığı için kapatmaya gerek yok.
