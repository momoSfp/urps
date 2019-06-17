<?php

namespace App\Utils;

use App\Entity\User;
use App\Entity\Tutor;

class Mailer
{
    protected $mailer;

    protected $emailFrom;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->emailFrom = "service@plateforme-etp-urps-ml-paca.fr";
    }

    public function sendMail($subject, $body, $to)
    {
        $message = $this->mailer->createMessage();

        $message->setFrom($this->emailFrom)
                ->setSubject("Urps - " . $subject)
                ->setBody($body, 'text/html')
                ->setTo($to);
                
        try {
            $this->mailer->send($message);
        } catch (\Swift_TransportException $Ste) {
            echo "EROORRRRRRRRRRRRRRRRRRRR\n\n\n";
        }
    }

    public function getMailSubjectWelcome()
    {
        return "Bienvenue sur la plateforme etp";
    }

    public function getMailSubjectResetPassword()
    {
        return "Réinitialiser mot de passe";
    }

    public function getMailBodyWelcome(User $user, $url)
    {
        $body  = "<h1 style='font-size:30px;padding-right:30px;padding-left:30px'>Bienvenue " . $user->getFirstname() . ",</h1>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Nous sommes ravis de vous avoir à bord.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Pour référence, voici vos informations de connexion:</p>";
        $body .= "<div style='padding-right:30px;padding-left:30px'><table style='table-layout:fixed;border:1px solid #a0a0a2;border-radius:8px;padding:40px 0;margin-top:20px;width:100%;border-collapse:separate;text-align:center'><tbody><tr><td style='vertical-align:middle'><h4 style='margin-bottom:2px;font-size:17px;font-weight:400'>Lien du site : <a href='" . $url . "' style='white-space:nowrap;color:#0576b9' target='_blank'>" . $url . "</a></h4><h4 style='margin-bottom:0;font-size:17px;font-weight:400'></td></tr></tbody></table></div>"; 
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Si vous avez des questions, n'hésitez pas à envoyer un courrier électronique à notre équipe de service à la clientèle truc@truc.fr. (Nous répondons très rapidement.) </p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Merci</p>";        
        $body .= "<div style='border-top:1px solid #e1e1e4;padding:30px 0 12px;margin-top:30px'>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>© 2019 Urps. Tous les droits sont réservés.</p>";
        $body .= "</div>";

        return $this->getMailTemplate($body);
    }

    public function getMailTutorBodyWelcome(Tutor $tutor, $url)
    {
        $body  = "<h1 style='font-size:30px;padding-right:30px;padding-left:30px'>Bienvenue Dr " . $tutor->getUserRelation()->getFirstname() . " " . $tutor->getUserRelation()->getLastname() . ",</h1>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Nous sommes ravis de vous avoir à bord.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Pour référence, voici vos informations de connexion:</p>";
        $body .= "<div style='padding-right:30px;padding-left:30px'><table style='table-layout:fixed;border:1px solid #a0a0a2;border-radius:8px;padding:40px 0;margin-top:20px;width:100%;border-collapse:separate;text-align:center'><tbody><tr><td style='vertical-align:middle'><h4 style='margin-bottom:2px;font-size:17px;font-weight:400'>Lien du site : <a href='" . $url . "' style='white-space:nowrap;color:#0576b9' target='_blank'>" . $url . "</a></h4><h4 style='margin-bottom:0;font-size:17px;font-weight:400'>Mot de passe : <strong>" . $tutor->getPlainTextPass() . "</strong></h4></td></tr></tbody></table></div>"; 
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Si vous avez des questions, n'hésitez pas à envoyer un courrier électronique à notre équipe de service à la clientèle truc@truc.fr. (Nous répondons très rapidement.) </p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Merci</p>";        
        $body .= "<div style='border-top:1px solid #e1e1e4;padding:30px 0 12px;margin-top:30px'>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>© 2019 Urps. Tous les droits sont réservés.</p>";
        $body .= "</div>";

        return $this->getMailTemplate($body);
    }

    public function getMailBodyResetPassword($url)
    {
        $body  = "<h1 style='font-size:30px;padding-right:30px;padding-left:30px'>Bonjour,</h1>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Vous avez récemment demandé à réinitialiser votre mot de passe pour votre compte.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Utilisez le bouton ci-dessous pour le réinitialiser. Cette réinitialisation du mot de passe n'est valide que pour les prochaines 24 heures.</p>";
        $body .= "<br>";
        $body .= "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='center'><a href='" . $url ."' style='padding: 10px; tex-align: center; color: white; background-color: #2ab178; font-size: 20px; border-radius:10px;'>Réinitialisé le mot de passe</a></td></tr></table>";
        $body .= "<br>";        
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Si vous n'avez pas demandé de réinitialisation de mot de passe, veuillez ignorer cet email ou contacter le support.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Merci</p>";
        $body .= "<div style='border-top:1px solid #e1e1e4;padding:30px 0 12px;margin-top:30px'>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Si vous rencontrez des problèmes avec le bouton ci-dessus, copiez et collez l’URL ci-dessous dans votre navigateur Web</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>" . $url . "</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>© 2019 Urps. Tous les droits sont réservés.</p>";
        $body .= "</div>";


        
        return $this->getMailTemplate($body);
    }

    public function getMailTemplate($body)
    {
        $content = "<table style='background-color: #e4e4e4;padding-top:20px;color:#434245;width:100%;border:0;text-align:center;'> <tbody> <tr> <td style='vertical-align:top;padding:0'> <center> <table id='m_4144290773013187565body' class='m_4144290773013187565card' style='border:0;border-collapse:collapse;margin:0 auto;background: white;border-radius:8px;margin-bottom:16px;'> <tbody> <tr> <td style='width:546px;vertical-align:top;padding-top:32px'> <div style='max-width:600px;margin:0 auto'> <img style='width:168px;margin:0 0 15px 0;padding-right:30px;padding-left:30px' src='<img style='width:168px;margin:0 0 15px 0;padding-right:30px;padding-left:30px' src='https://santeformapro.com/mail_com/images/logo_urps.jpg'>";
        $content .=  $body;
        $content .= "</div></td></tr></tbody></table></center></td></tr></tbody></table>";

        return $content;
    }
}