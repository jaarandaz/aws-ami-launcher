# AWS AMI Launcher

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

AWS AMI Launcher is a proof of concept to launch (and destroy) an specific AWS AMI using user's AWS credentials. Credentials are kept at front end.

## Installing on Bitnami

If you have a Bitnami LAMP insance you can follow these instrucitons to get the application running:

```
$> cd apps
$> sudo git clone https://github.com/jaarandaz/aws-ami-launcher.git
$> sudo chown -R bitnami aws-ami-launcher
$> cd aws-ami-launcher
$> composer install
$> cp .env.example .env
$> php artisan key:generate
$> cd ..
$> sudo chown -R bitnami:daemon aws-ami-launcher
$> sudo chmod -R g+w aws-ami-launcher/storage
```

Now we update Apache and Php-fpm configuration, and restart them:

```
$> sudo su
#> echo 'Include "/opt/bitnami/apps/aws-ami-launcher/conf/httpd-prefix.conf"' >> /opt/bitnami/apache2/conf/bitnami/bitnami-apps-prefix.conf
#> echo 'include=/opt/bitnami/apps/aws-ami-launcher/conf/php-fpm/pool.conf' >> /opt/bitnami/php/etc/php-fpm.conf
#> /opt/bitnami/ctlscript.sh restart
#> exit
$>
```

Now you should be able to access to the application at `http://$INSTANCE_IP/aws-ami-launcher`

## Contributing

Thank you for considering contributing to AWS AMI Launcher!.

## Security Vulnerabilities

This application should be for local use only.

If you discover a security vulnerability within AWS AMI Launcher, please send me a pull request or post an issue. All security vulnerabilities will be promptly addressed.

## License

The AWS AMI Launcher is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
