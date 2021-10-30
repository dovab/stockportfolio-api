<?php

namespace App\Application\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * Service to send emails
 */
class EmailService
{
    /**
     * @param LogService $logService
     * @param MailerInterface $mailer
     * @param SettingService $settingService
     */
    public function __construct(
        private LogService $logService,
        private MailerInterface $mailer,
        private SettingService $settingService
    ) {}

    /**
     * @param string $template
     * @param array|string $to
     * @param string $subject
     * @param array $templateVariables
     */
    public function send(string $template, array|string $to, string $subject, array $templateVariables = []): void
    {
        $toAddress = is_array($to) ? new Address($to['address'], $to['name']) : new Address($to);

        $email = (new TemplatedEmail())
            ->to($toAddress)
            ->subject($subject)
            ->context($templateVariables)
            ->htmlTemplate(sprintf('emails/%s.html.twig', $template))
            ->textTemplate(sprintf('emails/%s.text.twig', $template));

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logService->warning(
                sprintf('Cannot send email message: %s', $e->getMessage()),
                [
                    'exception' => $e,
                    'template' => $template,
                    'to' => $to,
                    'subject' => $subject,
                    'templateVariables' => $templateVariables,
                ]
            );
        }
    }

    /**
     * @param string $template
     * @param User $user
     * @param string $subject
     * @param array $templateVariables
     */
    public function sendToUser(string $template, User $user, string $subject, array $templateVariables = []): void
    {
        $this->send(
            $template,
            ['address' => $user->getEmail(), 'name' => $user->getName()],
            $subject,
            $templateVariables
        );
    }
}