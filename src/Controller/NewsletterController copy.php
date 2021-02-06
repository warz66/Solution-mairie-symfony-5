<?php

namespace App\Controller;

use DrewM\MailChimp\MailChimp;
use App\Entity\NewsletterSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\NewsletterSubscriberRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class NewsletterController extends AbstractController
{   

    /**
     * @Route("/newsletter", name="newsletter")
     */
    public function newsletter() 
    {
        return $this->render('newsletter.html.twig', [

        ]);
    }

    /**
     * @Route("/newsletter/inscription", name="newsletter_inscription")
     */
    public function inscription(Request $request, TokenGeneratorInterface $tokenGenerator, MailerInterface $mailer, EntityManagerInterface $manager, NewsletterSubscriberRepository $repo)
    {

        if ($request->isMethod('POST')) {

            // on gére le honeypot
            if (!empty($request->request->get('username'))) {
                return $this->redirectToRoute('homepage');
            }

            // on gére la protection google recaptcha
            $urlRecaptcha = "https://www.google.com/recaptcha/api/siteverify?secret={$this->getParameter('google_recaptcha_secret')}&response={$request->request->get('g-recaptcha-response')}";

            try {
                $client = HttpClient::create();
                $response = $client->request('GET', $urlRecaptcha)->toArray();
                if(!$response['success']) {
                    throw new \Exception;  
                }
            } catch (\Exception $e) {
                $this->addFlash("danger", "Une erreur est survenue lors de la réponse google recaptcha, veuillez réessayer, si ce problème persiste, contacter nous via le formulaire de contact.");
                return $this->redirectToRoute('newsletter');
            }

            // on bloque le nombre d'inscription avec la même adresse ip pendant un certain temps
            $ipAdress = $request->getClientIp();
            $delayMinutes = 15;
            $nbIpFind = $repo->findByIpAdress($ipAdress,$delayMinutes);

            if ($nbIpFind >= 3) {
                $this->addFlash('danger', 'Vous avez dépassé le nombre d\'inscriptions autorisé.');
                return $this->redirectToRoute('newsletter');    
            }

            // on regarde si l'email existe déjà et si l'utilisateur est deja inscrit sur mailchimp
            $email = $request->request->get('emailInscription');
            $subscriber = $repo->findOneByEmail($email);

            $mailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));
            $list_id = $this->getParameter('mailchimp_list_id');
            $subscriber_hash = MailChimp::subscriberHash($email);
            $response = $mailChimp->get("lists/$list_id/members/$subscriber_hash");

            if ( null !== $subscriber && $response['status'] === 'subscribed') {
                $this->addFlash('danger', 'Vous êtes déjà inscrit à la newsletter avec cette adresse email.');
                return $this->redirectToRoute('newsletter');
            }

            $token = $tokenGenerator->generateToken();
            if ( null === $subscriber ) {
                $subscriber = new NewsletterSubscriber;
                $subscriber ->setEmail($email)
                            ->setConfirmation(0) 
                            ->setIpAdress($ipAdress) 
                            ->setToken($token);
            } else {
                $subscriber ->setConfirmation(0) 
                            ->setIpAdress($ipAdress) 
                            ->setToken($token);
            }
            $manager->persist($subscriber);           
            $manager->flush();

            $url = $this->generateUrl('newsletter_confirmation', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new TemplatedEmail())
                ->from($this->getParameter('adresse_email_serveur')) // mettre l'email du serveur smtp en variable global symfony
                ->to($subscriber->getEmail())
                ->subject('Demande de confirmation à la newsletter de la mairie de ... ') // mettre le nom de la mairie en variable globale
                ->htmlTemplate('partials/email_confirmation_newsletter.html.twig')
                ->context([
                    'url' => $url,
                    'subscriber' => $subscriber
                ]);

            $mailer->send($email);
            
            $this->addFlash('success', 'Un email de confirmation à la newsletter vous a été envoyé !');

            return $this->redirectToRoute('newsletter');
        }

        return $this->render('newsletter.html.twig');
    }

    /**
     * @Route("/newsletter/confirmation/{token}", name="newsletter_confirmation")
     */
    public function confirmation($token, EntityManagerInterface $manager, NewsLetterSubscriberRepository $repo) {

        $subscriber = $repo->findOneByToken($token);
        if ($subscriber === null) {
            $this->addFlash('danger', 'Échec de confirmation d\'inscription à la newsletter, veuillez réessayer la procédure d\'inscription.');
            return $this->redirectToRoute('newsletter');
        }

        $mailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));
        $list_id = $this->getParameter('mailchimp_list_id');
        $mailChimp->post("lists/$list_id/members", [
            'email_address' => $subscriber->getEmail(),
            'status' => 'pending' //subscribed
        ]);

        if ($mailChimp->success()) {
            $subscriber->setConfirmation(1);
            $manager->persist($subscriber);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre inscription à la newsletter à bien été prise en compte !"
            );	
        } else {
            $manager->remove($subscriber);
            $manager->flush();
            $this->addFlash(
                'danger',
                "Votre inscription à la newsletter à échouer: ". $mailChimp->getLastError(). " Veuillez recommencer la procédure."
            );
        }
        
        return $this->render('newsletter.html.twig', [
            ]);
    }

    /**
     * @Route("/newsletter/desinscription", name="newsletter_desinscription")
     */
    public function desinscription(EntityManagerInterface $manager, NewsLetterSubscriberRepository $repo, Request $request) {

        if ($request->isMethod('POST')) {

            // on gére le honeypot
            if (!empty($request->request->get('username2'))) {
                return $this->redirectToRoute('homepage');
            }

            /*
            // on gére la protection google recaptcha
            $urlRecaptcha = "https://www.google.com/recaptcha/api/siteverify?secret={$this->getParameter('google_recaptcha_secret')}&response={$request->request->get('g-recaptcha-response')}";

            try {
                $client = HttpClient::create();
                $response = $client->request('GET', $urlRecaptcha)->toArray();
                if(!$response['success']) {
                    throw new \Exception;  
                }
            } catch (\Exception $e) {
                $this->addFlash("danger", "Une erreur est survenue lors de la réponse google recaptcha, veuillez réessayer, si ce problème persiste, contacter nous via le formulaire de contact.");
                return $this->redirectToRoute('newsletter');
            }
            */

            // on gére la supression à la newsletter
            $email = $request->request->get('emailDesinscription');
            $subscriber = $repo->findOneByEmail($email);

            $mailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));
            $list_id = $this->getParameter('mailchimp_list_id');
            $subscriber_hash = MailChimp::subscriberHash($email);
            $mailChimp->get("lists/$list_id/members/$subscriber_hash");

            if ($subscriber !== null || $mailChimp->success()) {
                $mailChimp->delete("lists/$list_id/members/$subscriber_hash");
                if ($subscriber !== null) {
                    $manager->remove($subscriber);
                    $manager->flush();
                }
                $this->addFlash('success', 'Vous avez bien été désinscrit de la newsletter.');
                return $this->redirectToRoute('newsletter'); // à corriger la route sur la homepage une fois les messages flashs implémenter sur cette page
            } else {
                $this->addFlash('danger', "Votre désinscription à la newsletter à échouer: ". $mailChimp->getLastError(). ", ou email inexistant de notre base de données.");  
            }
        }
        return $this->render('newsletter.html.twig', [
            ]);
    }
}
