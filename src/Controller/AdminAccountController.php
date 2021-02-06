<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\ResetPassword;
use App\Entity\UpdatePassword;
use App\Form\ResetPasswordType;
use App\Form\UpdatePasswordType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LoginFailedAttemptRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class AdminAccountController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils, request $request, LoginFailedAttemptRepository $repo)
    {   
        $delayRetryLogin = 15; // délai d'attente aprés x echecs de connexion
        $nbAttemptError = 5; // limite d'echecs de connexion 

        // on nettoie la table login_failed_attempt des anciennes echecs de connexion supérieure au délai d'attente
        $repo->cleanLoginFailedAttempts($delayRetryLogin);

        // on vérifie que le nombre d'échecs n'a pas atteint la limite prédéfinie
        if ($repo->countRecentLoginFailedAttempts($request->getClientIp(), $delayRetryLogin)>=$nbAttemptError) {
            return $this->render('admin/account/exceeded_failed_attempts.html.twig');
        }

        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
            'error' => $error,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/admin/logout", name="admin_account_logout")
     * 
     * @return void
     */
    public function logout() {
        //...
    }

    /**
     * Permet de modifier son profil
     * @Route("/admin/account/profile", name="admin_account_profile")
     */
    public function profil(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {

        $UpdatePassword = new UpdatePassword();
        $formPassword = $this->createForm(UpdatePasswordType::class, $UpdatePassword);

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('role')->remove('informations');

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $manager->persist($user);
                $manager->flush();
                
                $this->addFlash(
                    'success',
                    "Vos informations personnelles ont bien été modifiées !"
                );
            } else {
                $this->addFlash(
                    'danger',
                    "Attention, vos informations personnelles n'ont pu être sauvegardées, vérifier les messages d'erreurs !"
                );
            }
        }

        $formPassword->handleRequest($request);

        if ($formPassword->isSubmitted()) {
            if ($formPassword->isValid()) {
                
                if($encoder->isPasswordValid($user, $UpdatePassword->getOldPassword())) {

                    $newPassword = $UpdatePassword->getNewPassword();
                    $hash = $encoder->encodePassword($user, $newPassword);
    
                    $user->setHash($hash);
    
                    $manager->persist($user);
                    $manager->flush();
    
                    $this->addFlash(
                        'success',
                        "Votre mot de passe a bien été modifié !"
                    );
                } else {
                    $formPassword->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
                    $this->addFlash(
                        'danger',
                        "Attention, votre mot de passe n'a pu être modifié, vérifier les messages d'erreurs !"
                    );
                }

            } else {
                $this->addFlash(
                    'danger',
                    "Attention, votre mot de passe n'a pu être modifié, vérifier les messages d'erreurs !"
                );
            }
        }

        return $this->render('admin/account/profile.html.twig', [
            'form' => $form->createView(),
            'formPassword' => $formPassword->createView(),
            ]);
    }

    /**
     * @Route("/admin/login/forgotten-password", name="admin_account_forgotten_password")
     */
    public function forgottenPassword(Request $request, TokenGeneratorInterface $tokenGenerator, MailerInterface $mailer, EntityManagerInterface $manager, UserRepository $repo)
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
                $this->addFlash("danger", "Une erreur est survenue lors de la réponse google recaptcha, veuillez réessayer, si ce problème persiste, contacter l'administrateur.");
                return $this->redirectToRoute('admin_account_forgotten_password');
            }

            $email = $request->request->get('email');
            $user = $repo->findOneByEmail($email);

            if ($user === null) {
                $this->addFlash('danger', 'Adresse email non reconnu !');
                return $this->redirectToRoute('admin_account_forgotten_password');
            }

            if ( null !== $user->getDateToken()) {
                if (((new \DateTime('now'))->getTimestamp() - $user->getDateToken()->getTimestamp()) < 3600) {
                    $this->addFlash('danger', 'Une demande de changement de mot de passe à déjà était envoyé sur cette adresse mail, réessayer dans une heure !');
                    return $this->redirectToRoute('admin_account_forgotten_password');
                }
            }

            $token = $tokenGenerator->generateToken();

            try {
                $user->setToken($token);
                $user->setDateToken(new \DateTime('now'));
                $manager->flush();
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
                return $this->redirectToRoute('admin_account_forgotten_password');
            }

            $url = $this->generateUrl('admin_account_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new TemplatedEmail())
                ->from($this->getParameter('adresse_email_serveur'))
                ->to($user->getEmail())
                ->subject('Notification, mot de passe oublié, mairie de '.$this->getParameter('nom_mairie'))
                ->htmlTemplate('admin/account/email_forgotten_password.html.twig')
                ->context([
                    'url' => $url,
                    'user' => $user
                ]);

            $mailer->send($email);
            
            $this->addFlash('success', 'Email envoyé !');

            return $this->redirectToRoute('admin_account_forgotten_password');
        }

        return $this->render('admin/account/forgotten_password.html.twig');
    }

    /**
     * @Route("/admin/login/reset-password/{token}", name="admin_account_reset_password")
     */
    public function resetPassword($token, Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager, UserRepository $repo) {

        $user = $repo->findOneByToken($token);

        if ($user === null) {
            $this->addFlash('danger', 'Token non valide !');
            return $this->redirectToRoute('admin_account_login');
        }

        if(((new \DateTime('now'))->getTimestamp() - $user->getDateToken()->getTimestamp()) > 3600) {
            $this->addFlash('danger', 'Token expiré, vous aviez une heure pour changer votre mot de passe !');
            return $this->redirectToRoute('admin_account_login');
        }

        $passwordUpdate = new ResetPassword();
        $form = $this->createForm(ResetPasswordType::class, $passwordUpdate);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            if($form->isValid()) {
 
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);
                $user->setToken(null);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );
                return $this->redirectToRoute('admin_account_login');
            } else {
                $this->addFlash(
                    'danger',
                    "Attention, ce nouveau mot de passe n'est pas valide !"
                );
            }
        }
        
        return $this->render('admin/account/reset_password.html.twig', [
            'form' => $form->createView()
            ]);
    }

}
