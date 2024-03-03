<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendMailerService extends AbstractController
{

    public function __construct(
        private readonly MailerInterface $mailer
    )
    {
        
    }

    public function sendEmail($home, $away, $ss, $league)
    {
        $email = (new TemplatedEmail())
        ->from('djenane.yassine73@gmail.com')
        ->to('djenane.yassine1@gmail.com')
        ->subject($home. ' vs ' .$away)
        // path of the Twig template to render
        ->htmlTemplate('emails/alertMatch.html.twig')
    
        // change locale used in the template, e.g. to match user's locale
        ->locale('fr')
    
        // pass variables (name => value) to the template
        ->context([
            'ss' => $ss,
            'home' => $home,
            'away' => $away,
            'league' => $league
        ]);

        $this->mailer->send($email);
        

    }
}