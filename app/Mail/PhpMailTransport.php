<?php

namespace App\Mail;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

class PhpMailTransport extends AbstractTransport
{
    protected function doSend(SentMessage $message): void
    {
        $envelope = $message->getEnvelope();
        $sender = $envelope->getSender()->getAddress();
        $recipients = implode(', ', array_map(fn($r) => $r->getAddress(), $envelope->getRecipients()));

        $rawMessage = $message->getOriginalMessage();
        if ($rawMessage instanceof Email) {
            $email = $rawMessage;
        } else {
            $email = MessageConverter::toEmail($rawMessage);
        }

        $fromAddress = config('mail.from.address', 'bonjour@kimboo.net');
        $fromName = config('mail.from.name', 'Kimboo');

        $from = $email->getFrom();
        if (!empty($from)) {
            $fromHeader = $from[0]->toString();
            $senderEmail = $from[0]->getAddress();
        } else {
            $fromHeader = "{$fromName} <{$fromAddress}>";
            $senderEmail = $fromAddress;
        }

        $subject = $email->getSubject() ?? 'Notification Kimboo';
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        $htmlBody = $email->getHtmlBody();
        $textBody = $email->getTextBody();

        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = "From: {$fromHeader}";
        $headers[] = "Reply-To: {$fromHeader}";
        $headers[] = "X-Mailer: PHP/" . phpversion();

        if ($htmlBody) {
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            $headers[] = 'Content-Transfer-Encoding: 8bit';
            $body = is_resource($htmlBody) ? stream_get_contents($htmlBody) : (string) $htmlBody;
        } else {
            $headers[] = 'Content-Type: text/plain; charset=UTF-8';
            $headers[] = 'Content-Transfer-Encoding: 8bit';
            $body = is_resource($textBody) ? stream_get_contents($textBody) : (string) $textBody;
        }

        $extraParams = "-f" . escapeshellarg($senderEmail ?: $sender);
        $headerStr = implode("\r\n", $headers);

        $success = @mail($recipients, $encodedSubject, $body, $headerStr, $extraParams);
        if (!$success) {
            $success = @mail($recipients, $encodedSubject, $body, $headerStr);
        }

        if (!$success) {
            $lastError = error_get_last();
            $err = $lastError['message'] ?? 'Erreur inconnue de la fonction mail()';
            throw new \RuntimeException("Echec d'envoi de l'email via PHP mail() vers {$recipients} : {$err}");
        }
    }

    public function __toString(): string
    {
        return 'phpmail';
    }
}
