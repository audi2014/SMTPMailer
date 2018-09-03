```php
require "vendor/autoload.php";

use Audi2014\SmtpMailer\SmtpMailer;
$to = "______@_________";
$emailString = "emailString";
$subject = "subject";
$mailer = new SmtpMailer([
    SmtpMailer::CONFIG_FROM => "____________@_________",
    SmtpMailer::CONFIG_HOST => "smtp._________.___",
    SmtpMailer::CONFIG_PORT => 587,
    SmtpMailer::CONFIG_SECURE => "___",
    SmtpMailer::CONFIG_LOGIN => "____________@_________",
    SmtpMailer::CONFIG_PASSWORD => "_______________",
    SmtpMailer::CONFIG_LOGGER => 'html',
]);
echo $mailer->mailToUsers([$to], $emailString, $subject, $_FILES) ? 'ok' : 'error';
```