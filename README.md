<p align="center"><a href="https://github.com/Luca-Castelnuovo/MailJS"><img src="https://rawcdn.githack.com/Luca-Castelnuovo/MailJS/fc11a12f7c60df1275f15462a8309cec32e65db5/public/assets/images/banner.png"></a></p>

<p align="center">
<a href="https://github.com/Luca-Castelnuovo/MailJS/commits/master"><img src="https://img.shields.io/github/last-commit/Luca-Castelnuovo/MailJS" alt="Latest Commit"></a>
<a href="https://github.com/Luca-Castelnuovo/MailJS/issues"><img src="https://img.shields.io/github/issues/Luca-Castelnuovo/MailJS" alt="Issues"></a>
<a href="LICENSE.md"><img src="https://img.shields.io/github/license/Luca-Castelnuovo/MailJS" alt="License"></a>
</p>

# MailJS

Backend for email submissions powering serverless applications

-   [Homepage](https://mailjs.lucacastelnuovo.nl)
-   [SDK](https://github.com/Luca-Castelnuovo/MailJS-sdk)
-   [Docs](https://ltcastelnuovo.gitbook.io/mailjs/)

## Installation

For development

1. `git clone https://github.com/Luca-Castelnuovo/MailJS.git`
2. `composer install`
3. Edit `.env`
4. `php cubequence app:key`
5. `php cubequence app:jwt`
6. `php cubequence db:migrate`
7. `php cubequence db:seed`
8. Start development server `php -S localhost:8080 -t public`

For deployment

1. `git clone https://github.com/Luca-Castelnuovo/MailJS.git`
2. `composer install --optimize-autoloader --no-dev`
3. Edit `.env`
4. `php cubequence app:key`
5. `php cubequence app:jwt`
6. `php cubequence db:migrate`

## Security Vulnerabilities

Please review [our security policy](https://github.com/Luca-Castelnuovo/MailJS/security/policy) on how to report security vulnerabilities.

## License

Copyright © 2020 [Luca Castelnuovo](https://github.com/Luca-Castelnuovo).<br />
This project is [MIT](LICENSE.md) licensed.
