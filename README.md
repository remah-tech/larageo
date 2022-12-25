
# Laravel IP Geo Location Finder

**Larageo** is Laravel package helps developers to add value to their projects by fetching the IP geo details, like .e.g: IP, IP Version, Country, City, Region, Timezone, Locale, Currency, Time Now and more.

### Documentation
Full Documentation live here: [Larageo Docs](https://laravel.technoyer.com/larageo).

### Sample Usage
Using Facade:
```
Larageo::get();
```
Using Helper:
```
app('larageo')->get();
```
![Larageo Response Example](https://technoyer.com/img/larageo-response.jpg)

### Publish Config File
```
php artisan vendor:publish --provider="Technoyer\Larageo\Providers\LarageoServiceProvider"
```
You will find new file config/larageo.php
### Supported Drivers
- ip2location.io
- apiip.net
- ipapi.co
- ipapi.com
- ipgeolocation.io
- ipinfo.io
- maptiler.com

### How to Suggest Another Drivers
Please contact me and I will add your suggested drivers ASAP.

