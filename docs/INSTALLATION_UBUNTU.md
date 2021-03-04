## Odin installation guide for Ubuntu 20.04 LTS

This has been written for Ubuntu 20.04 LTS, but will likely work for future versions as well.
Installations for Debian will be fairly similar as well.

All commands should be run as root or with sudo.

### Odin
#### Add PHP and MariaDB repositories
- `apt -y install software-properties-common curl`
- `LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php`
- `curl -sS https://downloads.mariadb.com/MariaDB/mariadb_repo_setup | sudo bash`
- `apt update`

#### Install PHP and dependencies
- `apt install -y php7.4 php7.4-{dom,mbstring,curl,intl,opcache,ftp,gettext,iconv,json,phar,posix,readline,shmop,sockets,sysvmsg,sysvsem,sysvshm,tokenizer,fpm,pdo,mysqli}`

#### Node.js setup
- `curl -sL https://deb.nodesource.com/setup_current.x | sudo -E bash -`

#### Install dependencies
- `apt install -y curl unzip git nodejs nginx npm mariadb-server`

#### Install Composer
- `curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer`

#### Clone Odin from GitHub
- `cd /var/www`
- `git clone https://github.com/maelstrom-cms/odin.git`
- `cd /var/www/odin`

#### Run Composer to install required dependencies
- `composer install`

#### Run npm to install required Node.js dependencies
- `npm install`

#### Set up the database
- `mysql -u root -p`

> **Note:** If you just installed MySQL, and did not set up a password, you can just press enter when it asks for a password.

- `USE mysql;`
- `CREATE USER 'odin'@'127.0.0.1' IDENTIFIED BY 'somePassword';`

> **Note:** Replace `somePassword` here with your own password.

- `CREATE DATABASE odin;`
- `GRANT ALL PRIVILEGES ON odin.* TO 'odin'@'127.0.0.1' WITH GRANT OPTION;`
- `FLUSH PRIVILEGES;`
- `exit;`

#### .env File
- `cp .env.example .env`

> **Note:** You should modify this file to match what you need, and the details you set for the MySQL database.

#### Generate an application key
- `php artisan key:generate`

#### Link the storage directory
- `php artisan storage:link

#### Set up the database
- `php artisan migrate`

#### Test-run Odin
- `npm run prod`

> **Note:** You can also start in `dev` mode using `npm run dev`, or `watch` mode using `npm run watch`

If no errors are thrown, exit with ctrl+c

## Webserver configuration

> **Note:** This guide uses NGINX. Apache2 will probably work too.

#### Set required permissions
- `chown -R www-data:www-data /var/www/odin`
- `chmod -R u+rw /var/www/odin`

#### Set NGINX to start on boot
- `systemctl enable nginx`

#### NGINX configuration


> **Note:** You should change this file to work for your situation. For SSL, see NGINX guides on this topic :)  

Paste the following in `/etc/nginx/sites-available/odin.conf`

```
server {
    listen 80;
    server_name example.com
    root /var/www/odin/public/;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}

```
- Enable the configuration: `ln -s /etc/nginx/sites-available/odin.conf /etc/sites-enabled/odin.conf`
- Restart NGINX: `systemctl restart nginx`

#### Systemd service
- Paste the following in `/etc/systemd/system/odin.service`

```
[Unit]
Description=Odin application dashboard

[Service]
User=www-data
WorkingDirectory=/var/www/odin
ExecStart=npm run prod

[Install]
WantedBy=multi-user.target
```

- `systemctl daemon-reload`
- `systemctl enable --now odin`

## Using the Crawler

#### Install dependencies
- `apt install -y gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libappindicator1 libnss3 lsb-release xdg-utils wget`

#### Install Puppeteer
- `npm install --global --unsafe-perm puppeteer`

## Visual Diff

#### If you dont already have it, install Puppeteer
- `npm install --global --unsafe-perm puppeteer`

#### Install Browsershot
- `composer require spatie/browsershot`

#### Install Pixelmatch
- `npm i pixelmatch`