<?php

namespace App\Controller;

use DrewM\MailChimp\MailChimp;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function inscription(Request $request)
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

            // on regarde si l'email existe déjà et si l'utilisateur est deja inscrit sur mailchimp
            $email = $request->request->get('emailInscription');

            $mailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));
            $list_id = $this->getParameter('mailchimp_list_id');
            $subscriber_hash = MailChimp::subscriberHash($email);
            $response = $mailChimp->get("lists/$list_id/members/$subscriber_hash");

            if ($response['status'] === 'subscribed' || $response['status'] === 'pending') {
                $this->addFlash('danger', 'Vous êtes déjà inscrit à la newsletter avec cette adresse email, ou vous n\'avez pas confirmé votre inscription via l\'email de confirmation.');
                return $this->redirectToRoute('newsletter');
            }

            $mailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));
            $list_id = $this->getParameter('mailchimp_list_id');
            $mailChimp->post("lists/$list_id/members", [
                'email_address' => $email,
                'status' => 'pending' //subscribed
            ]);

            if ($mailChimp->success()) {
                $this->addFlash(
                    'success',
                    "Vous allez recevoir un email de confirmation d'enregistrement à notre newsletter, nous vous remercions de bien vouloir confirmer."
                );
            } else {
                $this->addFlash(
                    'danger',
                    "Votre inscription à la newsletter à échouer: ". $mailChimp->getLastError(). " Veuillez recommencer la procédure."
                );
            }    	
        }

        return $this->render('newsletter.html.twig');
    }

    /**
     * @Route("/newsletter/desinscription", name="newsletter_desinscription")
     */
    public function desinscription(Request $request) {

        if ($request->isMethod('POST')) {

            // on gére le honeypot
            if (!empty($request->request->get('username2'))) {
                return $this->redirectToRoute('homepage');
            }

            // on gére la supression à la newsletter
            $email = $request->request->get('emailDesinscription');

            $mailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));
            $list_id = $this->getParameter('mailchimp_list_id');
            $subscriber_hash = MailChimp::subscriberHash($email);
            $mailChimp->delete("lists/$list_id/members/$subscriber_hash");

            if ($mailChimp->success()) { 
                $this->addFlash('success', 'Vous avez bien été désinscrit de la newsletter.');
                return $this->redirectToRoute('newsletter'); // à corriger la route sur la homepage une fois les messages flashs implémenter sur cette page
            } else {
                $this->addFlash('danger', "Votre désinscription à la newsletter à échouer: ". $mailChimp->getLastError());  
            }
        }
        return $this->render('newsletter.html.twig', [
            ]);
    }
}
