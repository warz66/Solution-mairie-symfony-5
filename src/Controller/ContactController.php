<?php

namespace App\Controller;

use App\Entity\FormulaireContact;
use Symfony\Component\Mime\Email;
use App\Form\FormulaireContactType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer)
    {   

        $formulaireContact = new FormulaireContact();
        $form = $this->createForm(FormulaireContactType::class, $formulaireContact);

        $form->handleRequest($request);
  
        if ($form->isSubmitted()) {

            // on gére le honeypot
            if (!empty($request->request->get('username'))) {
                return $this->redirectToRoute('homepage');
            }

            // on gére la protection google recaptcha
            $urlRecaptcha = "https://www.google.com/recaptcha/api/siteverify?secret={$this->getParameter('google_recaptcha_v2_secret')}&response={$request->request->get('g-recaptcha-response')}";
            $client = HttpClient::create();
            $response = $client->request('GET', $urlRecaptcha)->toArray();

            if ($form->isValid() && $response['success']) {

                $email = (new Email())
                ->from($this->getParameter('adresse_email_serveur')) // l'adresse d'où le mail part.
                ->to($this->getParameter('contact_mail_reception')) // l'adresse où l'on souhaite recevoir le message pour y répondre.
                ->replyTo($formulaireContact->getEmail()) // l'adresse de la personne qui envoie le message.
                ->subject($formulaireContact->getObjet())
                ->text($formulaireContact->getMessage());

                $mailer->send($email);

                $this->addFlash(
                    'success',
                    "Votre message a bien été envoyé."
                );

                return $this->redirectToRoute('homepage');

            } else {
                if (!$response['success']) {
                    $this->addFlash("danger", "Une erreur est survenue lors de la réponse google recaptcha, veuillez réessayer, si ce problème persiste, contacter nous via votre logiciel de messagerie personnel, merci."); 
                } else {
                    $this->addFlash('danger', "Votre message n'est pas valide, veuillez vérifier s'il y a des erreurs.");
                }        
            }
        }       

        return $this->render('contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
