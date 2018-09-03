<?php
/**
 * Created by PhpStorm.
 * User: andriyprosekov
 * Date: 03/09/2018
 * Time: 10:41
 */

namespace Audi2014\SmtpMailer;


use PHPMailer\PHPMailer\PHPMailer;

class SmtpMailer implements MailerInterface {
    const CONFIG_HOST = 'host';
    const CONFIG_PORT = 'port';
    const CONFIG_LOGIN = 'login';
    const CONFIG_FROM = 'from';
    const CONFIG_PASSWORD = 'password';
    const CONFIG_SECURE = 'secure';
    const CONFIG_LOGGER = 'logger';
    private $host;
    private $port;
    private $login;
    private $password;
    private $secure;
    private $from;
    private $logger;

    public function __construct(array $cfg) {
        $this->host = $cfg[self::CONFIG_HOST];
        $this->port = $cfg[self::CONFIG_PORT];
        $this->login = $cfg[self::CONFIG_LOGIN];
        $this->password = $cfg[self::CONFIG_PASSWORD];
        $this->secure = $cfg[self::CONFIG_SECURE];
        $this->from = $cfg[self::CONFIG_FROM];
        $this->logger = $cfg[self::CONFIG_LOGGER] ?? 'error_log';
    }

    /**
     * @param array $emails
     * @param $html
     * @param $subject
     * @param array $files
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function mailToUsers(array $emails, $html, $subject, array $files = []) : bool {

        try {
            $mail = new PHPMailer(); // create a new object
            $mail->isSMTP(); // enable SMTP
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = $this->logger;
            //Ask for HTML-friendly debug output
            //$mail->Debugoutput = 'html';
            $mail->SMTPAuth = true; // authentication enabled
            $mail->SMTPSecure = $this->secure; // secure transfer enabled REQUIRED for GMail
            $mail->Host = $this->host;
            $mail->Port = $this->port; // or 587
            $mail->isHTML(true);
            $mail->Username = $this->login;
            $mail->Password = $this->password;
            $mail->setFrom($this->from);
            $mail->Subject = $subject;
            $mail->Body = $html;

            foreach ($emails as $key => $email) {
                $mail->AddAddress($email);
            }
            foreach ($files as $file) {
                if(is_array($file['tmp_name'])) {
                    foreach ($file['tmp_name'] as $key => $filePath) {
                        $filePath = $file['tmp_name'][$key];
                        $fileName = $file['name'][$key];
                        if (is_uploaded_file($filePath)) {
                            $mail->addAttachment($filePath, $fileName);
                        }

                    }
                } else {
                    $filePath = $file['tmp_name'];
                    $fileName = $file['name'];
                    if (is_uploaded_file($filePath)) {
                        $mail->addAttachment($filePath, $fileName);
                    }
                }
            }
            if (!$mail->Send()) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}