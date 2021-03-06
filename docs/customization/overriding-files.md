# Overriding files

### Overriding BREAD Views

You can override any of the BREAD views for a **single** BREAD by creating a new folder in `resources/views/vendor/laravel-cmf/slug-name` where _slug-name_ is the _slug_ that you have assigned for that table. There are 4 files that you can override:

* browse.blade.php
* edit-add.blade.php
* read.blade.php
* order.blade.php

Alternatively you can override the views for **all** BREADs by creating any of the above files under `resources/views/vendor/laravel-cmf/bread`

### Using custom Controllers

You can override the controller for a single BREAD by creating a controller which extends Voyagers controller, for example:

```php
<?php

namespace App\Http\Controllers;

class VoyagerCategoriesController extends \XADMIN\LaravelCmf\Http\Controllers\LaravelCmfBaseController
{
    //...
}
```

After that go to the BREAD-settings and fill in the Controller Name with your fully-qualified class-name:

![](../.gitbook/assets/bread_controller.png)

You can now override all methods from the [LaravelCmfBaseController](https://github.com/the-control-group/voyager/blob/1.1/src/Http/Controllers/LaravelCmfBaseController.php)

### Overriding Voyagers Controllers

If you want to override any of Voyagers core controllers you first have to change your config file `config/laravel-cmf.php`:

```php
/*
|--------------------------------------------------------------------------
| Controllers config
|--------------------------------------------------------------------------
|
| Here you can specify laravel-cmf controller settings
|
*/
​
'controllers' => [
    'namespace' => 'App\\Http\\Controllers\\LaravelCmf',
],
```

Then run `php artisan laravel-cmf:controllers`, LaravelCmf will now use the child controllers which will be created at `App/Http/Controllers/LaravelCmf`

### Overriding LaravelCmf-Models

You are also able to override LaravelCmf models if you need to.  
To do so, you need to add the following to your AppServiceProviders register method:

```php
LaravelCmf::useModel($name, $object);
```

Where **name** is the class-name of the model and **object** the fully-qualified name of your custom model. For example:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Events\Dispatcher;
use XADMIN\LaravelCmf\Facades\LaravelCmf;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        LaravelCmf::useModel('DataRow', \App\DataRow::class);
    }
    // ...
}
```

The next step is to create your model and make it extend the original model. In case of `DataRow`:

```php
<?php

namespace App;

class DataRow extends \XADMIN\LaravelCmf\Models\DataRow
{
    // ...
}
```
