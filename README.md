# MailJS

### Featues
- Mail Services (SMTP, Sendgrid)
- Mail Template (HandleBar)
- Captcha Integration (optional captcha requirement)
- Auto-Reply, if `<form>` auto-reply to `email`
- Allow JSON request OR `<form>` post
- If `<form>` also check for `<input type="hidden" name="redirect_to" value="https://example.com/success"`
- Origin check per project

### Idea

Authentication: similar to test.lucacastelnuovo.nl
Server: personal-server
DB: MariaDB

A `user` creates an `email`.
Project contains following settings:
- ProjectName
- Whitelisted Origin(s)
- ApiKey (hashed) (apikey should be used to determine which template needs to be used)
- `ToEmail`, `FromName`, `Subject` (all these can include variables from handlebar)
- Optional recaptcha requirement (the secret key will be asked)
- The email template
