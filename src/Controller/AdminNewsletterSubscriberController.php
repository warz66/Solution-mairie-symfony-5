<?php

namespace App\Controller;

use DrewM\MailChimp\MailChimp;
use App\Entity\NewsletterSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\NewsletterSubscriberRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminNewsletterSubscriberController extends AbstractController
{   
    /*
     * @Route("/admin/newsletter/mailing", name="admin_newsletter_mailing")
     */
    public function mailing() {
        return $this->render('admin/newsletter/mailing/mailing.html.twig');
    }

    /*
     * @Route("/admin/newsletter/abonnes/{page<\d+>?1}", name="admin_newsletter_abonnes_index")
     */
    public function index($page, NewsletterSubscriberRepository $repo, PaginatorInterface $paginator, Request $request)
    {   

        // on crée la pagination
        $nbPage = 50;
        $data = $repo->findBy([],['id' => 'DESC']);
        
        $subscribers = $paginator->paginate($data, $request->query->getInt('page',$page), $nbPage);
        $subscribers->setCustomParameters([
            'align' => 'center',
        ]);
        
        $formSubscriberAdd = $this->get('form.factory')->createNamedBuilder('formSubscribersAdd')
                ->setAction($this->generateUrl('admin_newsletter_abonne_add'))
                ->add('email', EmailType::class, [
                    'label' => false,
                    'required' => false,
                    'attr' => ['placeholder' => 'Ici vous pouvez ajouter un abonné(email).', 'maxlength' => '255'] 
                    ])->getForm();

        $formChoiceSubscribersDelete = $this->get('form.factory')->createNamedBuilder('formSubscribersDelete')
                ->setAction($this->generateUrl('admin_newsletter_abonnes_delete'))
                ->add('email', EntityType::class, [
                    'class' => NewsletterSubscriber::class,
                    'label' => false,
                    'choice_label' => 'email',
                    'multiple' => true,
                    'required' => false
                ])->getForm();


        return $this->render('admin/newsletter/abonnes/index.html.twig', [
            'subscribers' => $subscribers,
            'formChoiceSubscribersDelete' => $formChoiceSubscribersDelete->createView(),
            'formSubscriberAdd' => $formSubscriberAdd->createView()
        ]); 
    }

    /*
     * @Route("/admin/newsletter/abonne/{id}/delete", name="admin_newsletter_abonne_delete")
     */
    public function delete(NewsletterSubscriber $subscriber, EntityManagerInterface $manager, NewsletterSubscriberRepository $repo) {

        // on gére la supression à la newsletter
        $mailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));
        $list_id = $this->getParameter('mailchimp_list_id');
        $subscriber_hash = MailChimp::subscriberHash($subscriber->getEmail());
        $mailChimp->delete("lists/$list_id/members/$subscriber_hash");
        $mailChimp->get("lists/$list_id/members/$subscriber_hash");

        $existBdd = $repo->findOneByEmail($subscriber->getEmail());

        if (!$mailChimp->success() || ($existBdd !== null)) {
            $manager->remove($subscriber);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'abonné <strong>{$subscriber->getEmail()}</strong> a bien été supprimé !"
            );
        } else {
            $this->addFlash(
                'danger',
                "La désinscription à la newsletter de l'abonné <strong>{$subscriber->getEmail()}</strong> à échouer: ". $mailChimp->getLastError()
            );  
        }

        return $this->redirectToRoute("admin_newsletter_abonnes_index");
    }

    /*
     * @Route("/admin/newsletter/abonne/add", name="admin_newsletter_abonne_add")
     */
    public function subscriberAdd(EntityManagerInterface $manager, Request $request) {

        
        if (isset($request->request->get('formSubscribersAdd')['email'])) {
            $email = $request->request->get('formSubscribersAdd')['email'];

            if (!empty($email)) {

                $mailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));
                $list_id = $this->getParameter('mailchimp_list_id');
                $mailChimp->post("lists/$list_id/members", [
                    'email_address' => $email,
                    'status' => 'subscribed'
                ]);

                if ($mailChimp->success()) {
                    $subscriber = new NewsletterSubscriber();
                    $subscriber->setEmail($email)
                                ->setConfirmation(1);
    
                    $manager->persist($subscriber);
                    $manager->flush();
    
                    $this->addFlash(
                        'success',
                        "L'abonné <strong>{$subscriber->getEmail()}</strong> a bien été rajouté !"
                    );	
                } else {
                    $this->addFlash(
                        'danger',
                        "L'inscription à la newsletter à échouer: ". $mailChimp->getLastError()
                    );
                }
            }

        }

        return $this->redirectToRoute("admin_newsletter_abonnes_index");
    }

    /*
     * @Route("/admin/newsletter/abonnes/delete", name="admin_newsletter_abonnes_delete")
     */
    public function subscribersDelete(EntityManagerInterface $manager, Request $request, NewsletterSubscriberRepository $repo) {

        if (isset($request->request->get('formSubscribersDelete')['email'])) {

            $idSubscribers = $request->request->get('formSubscribersDelete')['email'];
            
            $mailChimp = new MailChimp($this->getParameter('mailchimp_api_key'));
            $list_id = $this->getParameter('mailchimp_list_id');

            foreach ($idSubscribers as $idSubscriber) {

                $subscriber = $repo->findOneById($idSubscriber);

                if (null !== $subscriber ) {
                    $subscriber_hash = MailChimp::subscriberHash($subscriber->getEmail());
                    $mailChimp->delete("lists/$list_id/members/$subscriber_hash");

                    $manager->remove($subscriber);
                    $manager->flush();
                }
            }

            $this->addFlash(
                'success',
                "Les abonnés ont bien été supprimés !"
            );
        }

        return $this->redirectToRoute("admin_newsletter_abonnes_index");
    }

    /*
     * @Route("/admin/newsletter/inscritsnonconfirmes/delete", name="admin_newsletter_inscritsnonconfirmes_delete")
     */
    public function inscritsNonConfirmesDelete(NewsletterSubscriberRepository $repo, EntityManagerInterface $manager) {

        $inscritsNonConfirmes = $repo->findByNonConfirmes();
        
        if (!empty($inscritsNonConfirmes)) {
            foreach($inscritsNonConfirmes as $inscritNonConfirme) {
                $manager->remove($inscritNonConfirme);
            }
            $manager->flush();
        }

        $this->addFlash(
            'success',
            "Les inscriptions non confirmés ont bien été supprimés !"
        );

        return $this->redirectToRoute("admin_newsletter_abonnes_index");
    }
}
