<h1 align="center">Welcome to MailJS 👋</h1>
<p>
  <a href="https://github.com/Luca-Castelnuovo/MailJS/blob/master/LICENSE" target="_blank">
    <img alt="License: MIT" src="https://img.shields.io/badge/License-MIT-yellow.svg" />
  </a>
</p>

> Backend for email submissions powering serverless aplications

### 🏠 [Homepage](https://mailjs.lucacastelnuovo.nl)

### 📖 [Docs](https://mailjs.lucacastelnuovo.nl/docs)

### 💾 [SDK](https://mailjs.lucacastelnuovo.nl/sdk)

## Install

1. Install Package
```sh
git clone https://github.com/Luca-Castelnuovo/MailJS.git

composer install
```
2. Create Gihub OAuth application
_the callback url should be https://your.app/auth/callback_

## Configuration

Edit .env

```bash
APP_URL=http://domain.com
APP_KEY=1234abcd
EXTERNAL_CONFIG=https://config.domain.com/config.json

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=database
DB_USERNAME=root
DB_PASSWORD=root

SMTP_HOST=smtp.example.com
SMTP_PORT=587
SMTP_USER=info@example.com
SMTP_PASSWORD=letmein

GITHUB_CIENT_ID=1234
GITHUB_CLIENT_SECRET=abcd
GITHUB_REDIRECT=https://domain.com/auth/callback
```

## 📝 License

Copyright © 2020 [Luca Castelnuovo](https://github.com/Luca-Castelnuovo).<br />
This project is [MIT](https://github.com/Luca-Castelnuovo/MailJS/blob/master/LICENSE) licensed.
