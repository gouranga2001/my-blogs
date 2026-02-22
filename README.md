#### create a new user

go to the project root dir and enter this command

```shell
php artisan tinker
```

then in the php shell run this code snippet and it will create a new user

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Suman',
    'username' => 'suman123',
    'email' => 'suman@email.com',
    'password' => Hash::make('StrongPassword123'),
]);
```


