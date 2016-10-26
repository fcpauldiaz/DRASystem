<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\UserBundle\Mailer\MailerInterface;

/**
 * Form overriden by  @author Pablo diaz <dia13203@uvg.edu.gt>.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class CustomTwigSwiftMailer implements MailerInterface
{
    protected $mailer;
    protected $router;
    protected $twig;
    protected $parameters;

    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, array $parameters)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->parameters = $parameters;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['confirmation'];
        $url = $this->router->generate('fos_user_registration_confirm', ['token' => $user->getConfirmationToken()], true);
        $context = [
            'user' => $user,
            'confirmationUrl' => $url,
        ];

        $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['resetting'];
        $url = $this->router->generate('fos_user_resetting_reset', ['token' => $user->getConfirmationToken()], true);
        $context = [
            'user' => $user,
            'confirmationUrl' => $url,
        ];
        $this->sendMessage($template, $context, $this->parameters['from_email']['resetting'], $user->getEmail());
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        //new instance
        $message = \Swift_Message::newInstance();
        $context = $this->twig->mergeGlobals($context); //merge context
        $template = $this->twig->loadTemplate($templateName);
        //espacio para agregar imágenes
        $context['image_src'] = $message->embed(\Swift_Image::fromPath('images/email_header.png')); //attach image 1
        $context['fb_image'] = $message->embed(\Swift_Image::fromPath('images/fb.gif')); //attach image 2
        $context['tw_image'] = $message->embed(\Swift_Image::fromPath('images/tw.gif')); //attach image 3
        $context['right_image'] = $message->embed(\Swift_Image::fromPath('images/right.gif')); //attach image 4
        $context['left_image'] = $message->embed(\Swift_Image::fromPath('images/left.gif')); //attach image 5
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }
}
