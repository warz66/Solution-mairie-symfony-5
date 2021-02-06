<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user_index")
     */
    public function index(UserRepository $repo)
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findBy([],['id' => 'DESC'])
        ]);
    }

    /**
     * @Route("/admin/user/new", name="admin_user_new")
     */
    public function new(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, Request $request, MailerInterface $mailer)
    {   
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les champs "Mot de passe" et "Confirmation du mot de passe" doivent être identiques',
            'required' => true,
            'first_options' => ['label' => 'Mot de passe', 'label_attr' => ['class' => 'font-weight-bold'], 'attr' => ['placeholder' => 'Au moins 8 caractères minimum']],
            'second_options' => ['label' => 'Confirmation du mot de passe', 'label_attr' => ['class' => 'font-weight-bold'], 'attr' => ['placeholder' => "Veuillez répéter le mot de passe à l'identique"]]
        ])->add('notification', CheckboxType::class, [
            'required' => false,
            'attr' => ['class' => 'mt-4'],
            'label_attr' => ['class' => 'font-weight-bold mt-3'],
            'label' => 'Envoyer une notification au nouvel utilisateur à propos de son compte ?',
            'mapped' => false
        ]);

        $form->handleRequest($request);
  
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                
                $hash = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();

                if (isset($request->request->get('user')['notification'])) {

                    $email = (new TemplatedEmail())
                    ->from($this->getParameter('adresse_email_serveur')) // mettre l'email du serveur smtp en variable global symfony
                    ->to($user->getEmail())
                    ->subject("Félicitations, votre compte d'administration pour le site web de la mairie de " . $this->getParameter('nom_mairie') . " viens d'être créé.")
                    ->htmlTemplate('admin/account/email_notification_profile.html.twig')
                    ->context([
                        'user' => $user
                    ]);
    
                    $mailer->send($email);
                }

                $this->addFlash(
                    'success',
                    "L'utilisateur <strong>{$user->getNom()}</strong> a bien été enregistrée !"
                );

                return $this->redirectToRoute('admin_user_index');
            } else {
                $this->addFlash(
                    'danger',
                    "Attention l'utilisateur n'a pu être sauvegardé, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/user/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'éditer un utilisateur
     * 
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     * 
     */
    public function edit(User $user, EntityManagerInterface $manager, Request $request)
    {   

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($user);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "L'utilisateur <strong>{$user->getNom()}</strong> a bien été modifiée !"
                );
                
                return $this->redirectToRoute('admin_user_index'); 
            } else {
                $this->addFlash(
                    'danger',
                    "Attention l'utilisateur n'a pu être sauvegardé, veuillez vérifier s'il y a des messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * Permet de supprimer un utilisateur
     * 
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     *
     */
    public function delete(User $user, EntityManagerInterface $manager) {

        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'utilisateur <strong>{$user->getNom()}</strong> a bien été supprimée !"
        );

        return $this->redirectToRoute("admin_user_index");
    }
}
