#CREATE THE SITE ON APACHE2
1.create a ongeza.wenyeji.com.conf in /etc/apache2/sites-available
The contents should be as follows
<VirtualHost *:80>
	# The ServerName directive sets the request scheme, hostname and port that
	# the server uses to identify itself. This is used when creating
	# redirection URLs. In the context of virtual hosts, the ServerName
	# specifies what hostname must appear in the request's Host: header to
	# match this virtual host. For the default virtual host (this file) this
	# value is not decisive as it is used as a last resort host regardless.
	# However, you must set it for any further virtual host explicitly.
	#ServerName www.example.com

	ServerName ongeza.wenyeji.com
	ServerAlias www.wenyeji.com
	ServerAdmin webmaster@localhost
	DocumentRoot /srv/apps/ongeza_rate/web

	# Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
	# error, crit, alert, emerg.
	# It is also possible to configure the loglevel for particular
	# modules, e.g.
	#LogLevel info ssl:warn

	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined

	# For most configuration files from conf-available/, which are
	# enabled or disabled at a global level, it is possible to
	# include a line for only one particular virtual host. For example the
	# following line enables the CGI configuration for this host only
	# after it has been globally disabled with "a2disconf".
	#Include conf-available/serve-cgi-bin.conf
        <Directory />
                Options FollowSymLinks
		AllowOverride All
		Require all granted
        </Directory>
        <Directory /srv/apps/ongeza_rate/web/>
                Options Indexes FollowSymLinks MultiViews
		AllowOverride All
	        Require all granted
    # use mod_rewrite for pretty URL support
    #RewriteEngine on
    # If a directory or a file exists, use the request directly
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    # Otherwise forward the request to index.php
    RewriteRule . index.php
        </Directory>

</VirtualHost>


2.enable the site on apache using the following command: sudo  a2ensite ongeza.wenyeji.com.conf 
3.Reload apache2 using the command sudo  service apache2 reload
4. in the /etc folder,add the following line in the hosts file
127.0.1.1       ongeza.wenyeji.com
5.restart apache2 server using the following command:  sudo service apache2 restart

#GET THE API DATA FOR THE PAST ONE MONTH
#The api has been built using yii framework,the developer must be having an understanding of how the yii framework works
1.Download TZSUSD exchange rate for the past 30 days in CSV format on the site below:
https://www.quandl.com/data/CURRFX/USDTZS-Currency-Exchange-Rates-USD-vs-TZS
2.create api controller
3.create a rates action method and insert the following code
        $file_name='CURRFX-USDTZS.csv';
        foreach (file($file_name) as $row) {
            $rower=  explode(',', $row);
            $y=  date('Y',  strtotime($rower[0]));
            $m=  date('m',  strtotime($rower[0]));
            $d=  date('d',  strtotime($rower[0]));
            $date="new Date(".$y.",".$m.",".$d.")";
            $date= trim($date);
            $data[]=array(
                $y,$m,$d,intval($rower[1]),  intval($rower[2]),  intval(str_replace("\n",'',$rower[3]))
            );
            
        }
        //remove the column names in the data
        array_shift($data);
        print_r(json_encode($data));

Yii 2 Basic Project Template
============================

Yii 2 Basic Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
rapidly creating small projects.

The template contains the basic features including user login/logout and a contact page.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.

[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii2-app-basic/v/stable.png)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://poser.pugx.org/yiisoft/yii2-app-basic/downloads.png)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Build Status](https://travis-ci.org/yiisoft/yii2-app-basic.svg?branch=master)](https://travis-ci.org/yiisoft/yii2-app-basic)

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Install from an Archive File

Extract the archive file downloaded from [yiiframework.com](http://www.yiiframework.com/download/) to
a directory named `basic` that is directly under the Web root.

You can then access the application through the following URL:

~~~
http://localhost/basic/web/
~~~


### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
php composer.phar global require "fxp/composer-asset-plugin:~1.0.0"
php composer.phar create-project --prefer-dist --stability=dev yiisoft/yii2-app-basic basic
~~~

Now you should be able to access the application through the following URL, assuming `basic` is the directory
directly under the Web root.

~~~
http://localhost/basic/web/
~~~


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTE:** Yii won't create the database for you, this has to be done manually before you can access it.

Also check and edit the other files in the `config/` directory to customize your application.
# ongeza_rate
