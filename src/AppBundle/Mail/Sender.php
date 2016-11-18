<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace AppBundle\Mail;

use AppBundle\Model\Contact;
use Symfony\Bundle\TwigBundle\TwigEngine as Templating;
use Swift_Mailer;
use Swift_Message;

class Sender
{
    /** @var \Swift_Mailer */
    protected $mailer;

    /** var \Symfony\Bundle\TwigBundle\TwigEngine */
    protected $templating;

    /** @var string */
    protected $senderEmail;

    /** @var string */
    protected $recipientEmail;

    /**
     * @param \Swift_Mailer $mailer
     */
    public function __construct(
        Swift_Mailer $mailer,
        Templating $templating,
        $senderEmail,
        $recipientEmail
    )
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->senderEmail = $senderEmail;
        $this->recipientEmail = $recipientEmail;
    }

    /**
     * @param Contact $contact
     */
    public function send(Contact $contact)
    {
        $title = 'You have a new message from ' . $contact->getFrom(); //todo handle this with translation : waiting for translation story
        $message = Swift_Message::newInstance($title, $contact->getMessage())
            ->setFrom($this->senderEmail)
            ->setTo($this->recipientEmail)
            ->setReplyTo($this->recipientEmail)
            ->setBody(
                $this->templating->render('/mail/contact.html.twig', [
                    'contact' => $contact
                ])
            );

        $this->mailer->send($message);
    }
}
