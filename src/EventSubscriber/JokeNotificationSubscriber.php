<?php

namespace App\EventSubscriber;

use App\Events;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Оповещение о шутке.
 */
class JokeNotificationSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $translator;
    private $sender;
    private $logger;

    public function __construct(\Swift_Mailer $mailer, TranslatorInterface $translator, LoggerInterface $logger, $sender)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->sender = $sender;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::JOKE_SEND => 'onJokeSend',
        ];
    }

    /**
     * Выполняет действия при событии отправки шутки.
     * @param GenericEvent $event
     * @throws Exception
     */
    public function onJokeSend(GenericEvent $event)
    {
        $jokeString = $event->getSubject();
        $jokeCategory = $event->getArgument("category_name");
        $emailTo = $event->getArgument("email_to");

        if (is_string($jokeCategory) && is_string($emailTo)) {
            $subject = $this->translator->trans('email.joke_random_from_category.subject', [
                '%category_name%' => $jokeCategory,
            ]);
            $body = $this->translator->trans('email.joke_random_from_category.body', [
                '%category_name%' => $jokeCategory, // Need to escape? Nope! :D
                '%joke_string%' => $jokeString
            ]);

            $message = (new \Swift_Message())
                ->setSubject($subject)
                ->setTo($emailTo)
                ->setFrom($this->sender)
                ->setBody($body, 'text/html')
            ;

            $this->mailer->send($message);

            $this->logger->info($jokeString);
        } else {
            throw new Exception("Error in notification.");
            // error...
        }
    }
}