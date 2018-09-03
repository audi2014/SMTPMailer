```php
$this->post('/mail', function (Request $request, $response, array $args) {

    $log = [];
    $logger = function($str, $level) use (&$log) {
        $log[]=$str;
    };


    $settings = $this->get(MessageSettingsInterface::class);
    $message = $request->getParsedBodyParam('message', '__EMPTY_MESSAGE__');
    $subject = $request->getParsedBodyParam('subject', '__EMPTY_subject__');
    $to = $request->getParsedBodyParam('to', 'audi2014@gmail.com');

    $emailString = (string)($this->get(Twig::class)->render(new \Slim\Http\Response(), 'email_message.html', [
        'content' => $message,
        'title' => 'TEST',
    ])->getBody());

    $mailer = new SMTPMailer([
        SMTPMailer::CONFIG_FROM => $settings->getMailFrom(),
        SMTPMailer::CONFIG_HOST => $settings->getMailSmtpHost(),
        SMTPMailer::CONFIG_PORT => $settings->getMailSmtpPort(),
        SMTPMailer::CONFIG_SECURE => $settings->getMailSmtpSecure(),
        SMTPMailer::CONFIG_LOGIN => $settings->getMailSmtpLogin(),
        SMTPMailer::CONFIG_PASSWORD => $settings->getMailSmtpPassword(),
        SMTPMailer::CONFIG_LOGGER => $logger,
    ]);

    $result = $mailer->mailToUsers([$to], $emailString, $subject, $_FILES) ? 'ok' : 'error';

    return $this->get(Twig::class)->render($response, 'mail-test.html', [
        'message' => $message,
        'subject' => $subject,
        'to' => $to,
        'response' => $result,
        'logs' => $log,
    ]);
});
```