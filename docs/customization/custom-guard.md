# Using a custom guard

Starting with LaravelCmf 1.2 you can define a (custom) guard which is used throughout LaravelCmf.  
To do so, just bind your auth-guard to `LaravelCmfAuth`.  
Open your `AuthServiceProvider` and add the following to the register method:  
```php
$this->app->singleton('LaravelCmfAuth', function () {
    return Auth::guard('your-custom-guard');
});
```
Now this guard is used instead of the default guard.
