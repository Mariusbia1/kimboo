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

        $subject = $email->getSubject() ?? '';
        $htmlBody = $email->getHtmlBody();
        $textBody = $email->getTextBody();

        $headers = [];
        $from = $email->getFrom();
        if (!empty($from)) {
            $headers[] = 'From: ' . $from[0]->toString();
            $headers[] = 'Reply-To: ' . $from[0]->toString();
        }

        if ($htmlBody) {
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            $body = is_resource($htmlBody) ? stream_get_contents($htmlBody) : (string) $htmlBody;
        } else {
            $headers[] = 'Content-Type: text/plain; charset=UTF-8';
            $body = is_resource($textBody) ? stream_get_contents($textBody) : (string) $textBody;
        }

        $extraParams = "-f" . escapeshellarg($sender);
        $headerStr = implode("\r\n", $headers);

        $success = @mail($recipients, $subject, $body, $headerStr, $extraParams);
        if (!$success) {
            $success = @mail($recipients, $subject, $body, $headerStr);
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
