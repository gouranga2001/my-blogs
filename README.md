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


#### apache config files are listed here

```shell
sudo nano /etc/apache2/sites-available/my-blogs.conf
```

#### certbot

Certbot is a free, open-source software tool developed by the Electronic Frontier Foundation (EFF) that automates the process of obtaining and renewing Let's Encrypt SSL/TLS certificates

#### Installation

-   ```shell
    sudo apt install certbot python3-certbot-apache -y
    ```
-   ```shell
    sudo certbot --apache -d amarblog.in -d www.amarblog.in
    ```
-   ```shell
    sudo nano /etc/apache2/sites-available/my-blogs.conf
    ```
    - inside the conf file

        ```shell
        <VirtualHost *:80>
            ServerName amarblog.in
            ServerAlias www.amarblog.in

            DocumentRoot /var/www/my-blogs/public

            <Directory /var/www/my-blogs/public>
                AllowOverride All
            Require all granted
            </Directory>

            ErrorLog ${APACHE_LOG_DIR}/error.log
            CustomLog ${APACHE_LOG_DIR}/access.log combined
        RewriteEngine on
        RewriteCond %{SERVER_NAME} =amarblog.in [OR]
        RewriteCond %{SERVER_NAME} =www.amarblog.in
        RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]
        </VirtualHost>
        ```
    
-   ```shell
    sudo a2ensite my-blogs.conf
    ```
-   ```shell
    sudo a2enmod rewrite
    ```
-   ```shell
    sudo systemctl reload apache2
    ```

