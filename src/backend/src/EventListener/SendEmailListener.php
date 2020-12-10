<?php

namespace App\EventListener;

use App\Entity\Enquiry;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendEmailListener implements EventSubscriber, LoggerAwareInterface
{
    use LoggerAwareTrait;

    const EMAIL_FROM = 'ilya.lavrentev@unbiased.co.uk';

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var string
     */
    private $notifyEmail;

    /**
     * @var Enquiry[]
     */
    private $scheduled = [];

    public function __construct(string $notifyEmail, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->notifyEmail = $notifyEmail;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Enquiry) {
            $this->schedule($entity);
        }
    }

    private function schedule(Enquiry $enquiry)
    {
        $this->scheduled[spl_object_hash($enquiry)] = $enquiry;
    }

    public function onKernelTerminate()
    {
        foreach ($this->scheduled as $enquiry) {
            $this->sendEmail($enquiry);
        }
    }

    private function sendEmail(Enquiry $enquiry)
    {
        $email = new Email();
        $email
            ->from(self::EMAIL_FROM)
            ->to($this->notifyEmail)
            ->subject('New enquiry')
            ->text(sprintf('New enquiry was created for %s', $enquiry->getFullName()))
        ;

        try {
            $this->mailer->send($email);
        } catch (\Throwable $e) {
            $this->logger->critical($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}