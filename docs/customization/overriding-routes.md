# Overriding Routes

You can override any LaravelCmf routes by writing the routes you want to overwrite below `LaravelCmf::routes()`. For example if you want to override your post LoginController:

```php
Route::group(['prefix' => 'admin'], function () {
   LaravelCmf::routes();

   // Your overwrites here
   Route::post('login', ['uses' => 'MyAuthController@postLogin', 'as' => 'postlogin']);
});
```

