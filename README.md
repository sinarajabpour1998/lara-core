# Lara Core
This package provides core features [access control list and user manager] for
laravel apps.

## Installation
Using Composer :

```bash
composer require sinarajabpour1998/lara-core
```

packagist : [lara-core](https://packagist.org/packages/sinarajabpour1998/lara-core)

## ACL Usage

* Change the user modal namespace to laratrust config 
  (located in `/config/laratrust.php`) in `user_models` section :

```php
'user_models' => [
    'users' => 'App\Models\User',
],
```

* Publish blade files

```bash
php artisan vendor:publish --tag=lara-core
```

** Please note if you already published the vendor, for updates you can run the 
following command :

```bash
php artisan vendor:publish --tag=lara-core --force
```

* Add the following tag in your sidebar layout :

```html
<x-acl-menu></x-acl-menu>
```

or shorten tag :

```html
<x-acl-menu />
```

## Google Recaptcha usage

Set the values for google api in .env file:
```
GOOGLE_RECAPTCHA_SITE_KEY=
GOOGLE_RECAPTCHA_SECRET_KEY=
```

Add this tag in blade files:
```
<x-cutlet-recaptcha :has-error="$errors->has('g-recaptcha-response')"></x-cutlet-recaptcha>
```

Add the validation rule:
```
protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => ['required', 'string'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required', 'cutlet_recaptcha']
        ]);
    }
```

Customize the language and validation message in config file:
```
return [
    'language' => 'fa',
    'site_key' => env('GOOGLE_RECAPTCAH_SITE_KEY'),
    'secret_key' => env('GOOGLE_RECAPTCAH_SECRET_KEY'),
    'message' => 'شما به عنوان ربات تشخیص داده شده‌اید'
];
```

## Config options

You can set custom permissions for each section of this package. make sure that you already specified permissions in a seeder.

