<?php

namespace App\Utils;

use App\Entity\User;
use App\Entity\Tutor;
use App\Entity\Content;

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

        $this->mailer->send($message);

    }

    public function getMailSubjectWelcome()
    {
        return "Bienvenue sur la plateforme etp";
    }

    public function getMailSubjectRegistreUser()
    {
        return "Inscription patient sur plateforme URPS-ML-PACA";
    }

    public function getMailSubjectEndGame()
    {
        return "achèvement jeux sérieux";
    }

    public function getMailSubjectResetPassword()
    {
        return "Réinitialiser mot de passe";
    }

    public function getMailBodyWelcome(User $user, $url)
    {
        $body  = "<h1 style='font-size:30px;padding-right:30px;padding-left:30px'>Bienvenue " . $user->getFirstname() . " " . $user->getLastname() . "</h1>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Nous sommes ravis de vous avoir à bord !</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Pour mémoire, voici le lien pour participer au jeu sérieux (serious game) qui vous a été recommandé :</p>";
        $body .= "<div style='padding-right:30px;padding-left:30px'><table style='table-layout:fixed;border:1px solid #a0a0a2;border-radius:8px;padding:40px 0;margin-top:20px;width:100%;border-collapse:separate;text-align:center'><tbody><tr><td style='vertical-align:middle'><h4 style='margin-bottom:2px;font-size:17px;font-weight:400'>Lien du site : <a href='" . $url . "' style='white-space:nowrap;color:#0576b9' target='_blank'>" . $url . "</a></h4><h4 style='margin-bottom:0;font-size:17px;font-weight:400'></td></tr></tbody></table></div>"; 
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Vous pouvez à tout moment modifier vos informations et votre mot de passe. Pour cela, il vous suffit de vous connecter à la plateforme en vous identifiant et d'ouvrir l'onglet \"Mes informations\", dans votre profil</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Votre médecin traitant pourra suivre votre avancement dans le jeu, afin d'adapter au mieux les conseils et informations qu'il vous délivrera lors de votre prochaine consultation. Seuls lui et l’administrateur de l'Union Régionale des Médecins Libéraux de la région PACA ont accès à ces informations.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Aucune démarche commerciale ne résultera de votre inscription, cette plateforme et les activités qu'elle héberge ont été créées dans un but strictement informatif et éducatif, afin de soutenir les patients et le suivi par leur médecin traitant.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Pour toute question, vous pouvez contacter l'assistance de la plateforme par
        courrier électronique, à l'adresse suivante : contact-plateforme@urps-ml-paca.org</p>";              
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Cordialement</p>";        
        $body .= "<div style='border-top:1px solid #e1e1e4;padding:30px 0 12px;margin-top:30px'>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>© 2019 Urps. Tous les droits sont réservés.</p>";
        $body .= "</div>";

        return $this->getMailTemplate($body);
    }

    public function getMailBodyRegistreUser(User $user, $tutorName)
    {
        $body  = "<h1 style='font-size:30px;padding-right:30px;padding-left:30px'>Bonjour " . $tutorName . "</h1>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Votre patient, <b>" . $user->getFirstname() . " " . $user->getLastname() . "</b> s'est inscrit sur la plateforme URPS-ML-PACA.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Vous pouvez dès à présent suivre son avancement dans le jeu. Pour cela, il vous suffit de vous connecter à la plateforme en vous identifiant et d'ouvrir l'onglet \"Mes patients\", dans votre profil.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Voici le lien pour vous connecter :<br>https://www.plateforme-etp-urps-ml-paca.fr/</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Cordialement</p>";        
        $body .= "<div style='border-top:1px solid #e1e1e4;padding:30px 0 12px;margin-top:30px'>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>© 2019 Urps. Tous les droits sont réservés.</p>";
        $body .= "</div>";

        return $this->getMailTemplate($body);
    }

    public function getMailBodyEndGame(User $user, Content $content, $url)
    {
        $body  = "<h1 style='font-size:30px;padding-right:30px;padding-left:30px'>Bravo " . $user->getFirstname() . " " . $user->getLastname() . " !</h1>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Vous avez terminé le jeu sérieux (serious game) " . $content->getTitle() . " !</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Si vous le souhaitez, vous pouvez à tout moment recommencer les activités qui vous ont été proposées dans le jeu en vous connectant à nouveau.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Votre médecin traitant est informé de votre avancement dans le jeu.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>N'hésitez pas à en parler avec lui.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Merci de bien vouloir noter le " . $content->getTitle() . "</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'><div style='padding-right:30px;padding-left:30px'><table style='table-layout:fixed;border:1px solid#a0a0a2;border-radius:8px;padding:40px0;margin-top:20px;width:100%;border-collapse:separate;text-align:center'><tbody><tr><td><a href='" . $url . "?u=" . ($user->getId() * 75) . "&c=" . ($content->getId() * 59) . "&r=" . (1 * 13) . "'><span style='border-radius: 5px;padding: 16px;background-color: #ff8b5a;color: white;font-size: 17px!important;'>1</span></a></td><td><a href='" . $url . "?u=" . ($user->getId() * 75) . "&c=" . ($content->getId() * 59) . "&r=" . (2 * 13) . "'><span style='border-radius: 5px;padding: 16px;background-color: #ffb233;color: white;font-size: 17px!important;'>2</span></a></td><td><a href='" . $url . "?u=" . ($user->getId() * 75) . "&c=" . ($content->getId() * 59) . "&r=" . (3 * 13) . "'><span style='padding: 16px;background-color: #ffea3a;color: white;font-size: 17px!important;border-radius: 5px;'>3</span></a></td><td><a href='" . $url . "?u=" . ($user->getId() * 75) . "&c=" . ($content->getId() * 59) . "&r=" . (4 * 13) . "'><span style='border-radius: 5px;padding: 16px;background-color: #ccdb38;color: white;font-size: 17px!important;'>4</span></a></td><td><a href='" . $url . "?u=" . ($user->getId() * 75) . "&c=" . ($content->getId() * 59) . "&r=" . (5 * 13) . "'><span style='border-radius: 5px;padding: 16px;background-color: #8ac249;color: white;font-size: 17px!important;'>5</span></a></td></tr></tbody></table></div></p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Cordialement</p>";
        $body .= "<div style='border-top:1px solid #e1e1e4;padding:30px 0 12px;margin-top:30px'>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>© 2019 Urps. Tous les droits sont réservés.</p>";
        $body .= "</div>";

        return $this->getMailTemplate($body);
    }

    public function getMailTutorBodyWelcome(Tutor $tutor, $url)
    {
        $body  = "<h1 style='font-size:30px;padding-right:30px;padding-left:30px'>Bienvenue Dr " . $tutor->getUserRelation()->getFirstname() . " " . $tutor->getUserRelation()->getLastname() . ",</h1>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Vous êtes bien inscrit(e) sur la plateforme de l'URPS-ML-PACA.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>PPour mémoire, voici vos informations de connexion :</p>";
        $body .= "<div style='padding-right:30px;padding-left:30px'><table style='table-layout:fixed;border:1px solid #a0a0a2;border-radius:8px;padding:40px 0;margin-top:20px;width:100%;border-collapse:separate;text-align:center'><tbody><tr><td style='vertical-align:middle'><h4 style='margin-bottom:2px;font-size:17px;font-weight:400'>Lien du site : <a href='" . $url . "' style='white-space:nowrap;color:#0576b9' target='_blank'>" . $url . "</a></h4><h4 style='margin-bottom:0;font-size:17px;font-weight:400'>Mot de passe : <strong>" . $tutor->getPlainTextPass() . "</strong></h4></td></tr></tbody></table></div>"; 
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Vous pouvez dés à présent suivre l'avancement de vos patients sur le(s) jeu(x) sérieux (serious game) que vous leur avez recommandé(s), s'ils s'y sont inscrits.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Pour cela, il vous suffit de vous connecter à la plateforme en vous identifiant et d'ouvrir l'onglet \"Mes patients\", dans votre profil.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Pour toute question, vous pouvez contacter l'assistance de la plateforme par courrier électronique, à l'adresse suivante : contact-plateforme@urps-ml-paca.org</p>";        
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Cordialement</p>";        
        $body .= "<div style='border-top:1px solid #e1e1e4;padding:30px 0 12px;margin-top:30px'>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>© 2019 Urps. Tous les droits sont réservés.</p>";
        $body .= "</div>";

        return $this->getMailTemplate($body);
    }

    public function getMailBodyResetPassword($url)
    {
        $body  = "<h1 style='font-size:30px;padding-right:30px;padding-left:30px'>Bonjour,</h1>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Vous avez récemment demandé à réinitialiser le mot de passe pour votre compte.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Cliquez sur le bouton ci-dessous pour terminer cette démarche.</p>";
        $body .= "<p style='font-size:17px;padding-right:30px;padding-left:30px'>Ce bouton n'est valide que pour les prochaines 24 heures.</p>";
        $body .= "<br>";
        $body .= "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='center'><a href='" . $url ."' style='padding: 10px; tex-align: center; color: white; background-color: #2ab178; font-size: 20px; border-radius:10px;'>Réinitialiser le mot de passe</a></td></tr></table>";
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