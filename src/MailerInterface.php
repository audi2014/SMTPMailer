<?php 

namespace Audi2014\SmtpMailer;

interface MailerInterface {

    public function mailToUsers(array $emails, $html, $subject, array $files = []) : bool;

}