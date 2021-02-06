<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Menu;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Galerie;
use App\Entity\Category;
use App\Entity\Rubrique;
use App\Entity\Actualite;
use App\Entity\Carrousel;
use App\Entity\Evenement;
use App\Entity\FlashInfo;
use App\Entity\Ressource;
use Cocur\Slugify\Slugify;
use App\Entity\AccesRapide;
use App\Entity\Information;
use App\Entity\Publication;
use App\Entity\CarrouselObjet;
use App\Entity\FlashInfoObjet;
use DrewM\MailChimp\MailChimp;
use App\Entity\AccesRapideObjet;
use App\Entity\LienUtileExterne;
use App\Entity\NewsletterSubscriber;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{   
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        
        $evenementCategory = array('Animation', 'Cinéma', 'Concert', 'Conférence', 'Danse', 'Exposition', 'Festival', 'Livre', 'Musique', 'Salon', 'Solidarité', 'Spectacle', 'Sport', 'Théâtre');
        $actualiteCategory = array('Appel à participation', 'Associations', 'Cadre de vie', 'Campagne d\'informations', 'Commémoration', 'Concertation', 'Conseils de quartier', 'Culture', 'Découvrir notre ville', 'Démarches et formalités', 'Démocratie participative', 'Déplacements', 'Développement durable', 'Economie','Education', 'Elections', 'Emploi', 'Enfance', 'Enfance et éducation', 'Etudiants', 'Gastronomie', 'Handicap', 'International', 'Loisirs', 'Marchés publics', 'Patrimoine', 'Professionnels', 'Projets urbains', 'Quartiers', 'Santé', 'Seniors', 'Solidarité', 'Sport', 'Stationnement', 'Tourisme', 'Urbanisme', 'Vie municipale');
        $allCategory = array_merge($actualiteCategory,$evenementCategory);

        $category1 = new Category;
        $category1->setNom('rubrique');
        $category2 = new Category;
        $category2->setNom('sous-rubrique');
        $category3 = new Category;
        $category3->setNom('page');
        $category4 = new Category;
        $category4->setNom('actualite');
        $category5 = new Category;
        $category5->setNom('evenement');

        $tabCat=[$category1,$category2,$category3,$category4,$category5];

        $manager->persist($category1);
        $manager->persist($category2);
        $manager->persist($category3);
        $manager->persist($category4);
        $manager->persist($category5);

        $adminRole2 = new Role();
        $adminRole2->setTitle('ROLE_EDITEUR');
        $manager->persist($adminRole2);
         
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
        $adminUser = new User();

        $adminUser->setNom('admin')
                  ->setPrenom('admin')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setEmail('admin@symfony.com')
                  ->setInformations('Administrateur générique')
                  ->setRole($adminRole);
        $manager->persist($adminUser);
        
        $menu = new Menu();
        $manager->persist($menu);

        $information = new Information();

        $information->setNom('information')
                    ->setAdresse('Mairie de Rosillon place de la liberté')
                    ->setCp('84950')
                    ->setVille('Rossillon')
                    ->setTelephone('04.92.14.89.18');
        $manager->persist($information);

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new PicsumPhotosProvider($faker));

        $slugify = new Slugify();

        // création rubrique
        for($i = 1;$i <= 10; $i++) {
            $publicationRubrique = new Publication();
            $statut = 1;
            $createdAt = $faker->dateTimeBetween('now');
            $updatedAt = $createdAt;
            $title = $faker->sentence(1.5);
            $slug = $slugify->slugify($title);
            $coverImage = $faker->imageUrl(1000,350,mt_rand(1,1000));
            $introduction = $faker->paragraph(2);
            
            $publicationRubrique->setTitle($title)
                ->setSlug($slug)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setCreateAt($createdAt)
                ->setUpdatedAt($updatedAt)
                ->setCategory($category1)
                ->setStatut($statut);

            $rubrique = new Rubrique();
            $arrayRandCategory = []; 
            $keys = array_rand($allCategory, mt_rand(2,6));
            foreach ($keys as $key) {
                $arrayRandCategory[] = $allCategory[$key];
            }   
            $rubrique->setPublication($publicationRubrique)
                     ->setCategory($arrayRandCategory);   

            
            for($j = 1;$j <= mt_rand(1,5); $j++) {
                $lienUtileExterne = new LienUtileExterne();
                
                $url = $faker->url();
                $title = $faker->sentence();
    
                $lienUtileExterne->setUrl($url)
                    ->setTitle($title)
                    ->setPublication($publicationRubrique);
                
                $manager->persist($lienUtileExterne); 
            }

            for($j = 1;$j <= mt_rand(1,5); $j++) {
                $ressource = new Ressource();
                
                $url = $faker->url();
                $updatedAt = $faker->dateTimeBetween('now');
                $title = $faker->sentence();
    
                $ressource->setUrl($url)
                    ->setTitle($title)
                    ->setUpdatedAt($updatedAt)
                    ->setPublication($publicationRubrique);
                
                $manager->persist($ressource); 
            }    

            //creation sous rubrique
            for($a = 1;$a <= mt_rand(1,5); $a++) {
                $publicationSousrubrique = new Publication();
                $statut = 1;
                $createdAt = $faker->dateTimeBetween('now');
                $updatedAt = $createdAt;
                $title = $faker->sentence();
                $slug = $slugify->slugify($title);
                $coverImage = $faker->imageUrl(1000,350,mt_rand(1,1000));
                $introduction = $faker->paragraph(2);

                $publicationSousrubrique->setTitle($title)
                ->setSlug($slug)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setCreateAt($createdAt)
                ->setUpdatedAt($updatedAt)
                ->setCategory($category2)
                ->setParent($publicationRubrique)
                ->setStatut($statut);

                
                $sousRubrique = new Rubrique();
                $arrayRandCategory = []; 
                $keys = array_rand($allCategory, mt_rand(2,4));
                foreach ($keys as $key) {
                    $arrayRandCategory[] = $allCategory[$key];
                }    
                $sousRubrique->setPublication($publicationSousrubrique)
                             ->setCategory($arrayRandCategory);

                for($j = 1;$j <= mt_rand(1,5); $j++) {
                    $lienUtileExterne = new LienUtileExterne();
                    
                    $url = $faker->url();
                    $title = $faker->sentence();
        
                    $lienUtileExterne->setUrl($url)
                        ->setTitle($title)
                        ->setPublication($publicationSousrubrique);
                    
                    $manager->persist($lienUtileExterne); 
                }
    
                for($j = 1;$j <= mt_rand(1,5); $j++) {
                    $ressource = new Ressource();
                    
                    $url = $faker->url();
                    $updatedAt = $faker->dateTimeBetween('now');
                    $title = $faker->sentence();
        
                    $ressource->setUrl($url)
                        ->setTitle($title)
                        ->setUpdatedAt($updatedAt)
                        ->setPublication($publicationSousrubrique);
                    
                    $manager->persist($ressource); 
                }

                // creation page dans sous rubrique
                for($b = 1;$b <= mt_rand(1,5); $b++) {
                    $publicationPage = new Publication();
                    $statut = random_int(0,1);
                    $createdAt = $faker->dateTimeBetween('now');
                    $updatedAt = $createdAt;
                    $title = $faker->sentence();
                    $slug = $slugify->slugify($title);
                    $coverImage = $faker->imageUrl(1000,350,mt_rand(1,1000));
                    $introduction = $faker->paragraph(2);
                    $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
    
                    $publicationPage->setTitle($title)
                    ->setSlug($slug)
                    ->setCoverImage($coverImage)
                    ->setIntroduction($introduction)
                    ->setContent($content)
                    ->setCreateAt($createdAt)
                    ->setUpdatedAt($updatedAt)
                    ->setCategory($category3)
                    ->setParent($publicationSousrubrique)
                    ->setStatut($statut);

                    for($j = 1;$j <= mt_rand(1,5); $j++) {
                        $lienUtileExterne = new LienUtileExterne();
                        
                        $url = $faker->url();
                        $title = $faker->sentence();
            
                        $lienUtileExterne->setUrl($url)
                            ->setTitle($title)
                            ->setPublication($publicationPage);
                        
                        $manager->persist($lienUtileExterne); 
                    }
        
                    for($j = 1;$j <= mt_rand(1,5); $j++) {
                        $ressource = new Ressource();
                        
                        $url = $faker->url();
                        $updatedAt = $faker->dateTimeBetween('now');
                        $title = $faker->sentence();
            
                        $ressource->setUrl($url)
                            ->setTitle($title)
                            ->setUpdatedAt($updatedAt)
                            ->setPublication($publicationPage);
                        
                        $manager->persist($ressource); 
                    }

                    $manager->persist($publicationPage);
                } 

                $manager->persist($sousRubrique);
                $manager->persist($publicationSousrubrique);
            }

            // creation page dans rubrique
            for($a = 1;$a <= mt_rand(1,5); $a++) {
                $publicationPage = new Publication();
                $statut = random_int(0,1);
                $createdAt = $faker->dateTimeBetween('now');
                $updatedAt = $createdAt;
                $title = $faker->sentence();
                $slug = $slugify->slugify($title);
                $coverImage = $faker->imageUrl(1000,350,mt_rand(1,1000));
                $introduction = $faker->paragraph(2);
                $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

                $publicationPage->setTitle($title)
                ->setSlug($slug)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setCreateAt($createdAt)
                ->setUpdatedAt($updatedAt)
                ->setCategory($category3)
                ->setParent($publicationRubrique)
                ->setStatut($statut);

                for($j = 1;$j <= mt_rand(1,5); $j++) {
                    $lienUtileExterne = new LienUtileExterne();
                    
                    $url = $faker->url();
                    $title = $faker->sentence();
        
                    $lienUtileExterne->setUrl($url)
                        ->setTitle($title)
                        ->setPublication($publicationPage);
                    
                    $manager->persist($lienUtileExterne); 
                }
    
                for($j = 1;$j <= mt_rand(1,5); $j++) {
                    $ressource = new Ressource();
                    
                    $url = $faker->url();
                    $updatedAt = $faker->dateTimeBetween('now');
                    $title = $faker->sentence();
        
                    $ressource->setUrl($url)
                        ->setTitle($title)
                        ->setUpdatedAt($updatedAt)
                        ->setPublication($publicationPage);
                    
                    $manager->persist($ressource); 
                }

                $manager->persist($publicationPage);
            }

            $manager->persist($rubrique);
            $manager->persist($publicationRubrique);
        }

        for($i = 1;$i <= 40; $i++) {
            $publication = new Publication();
            
            $coverImage = $faker->imageUrl(1000,350,mt_rand(1,1000));
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
            $createdAt = $faker->dateTimeBetween('-6 months');
            $updatedAt = $createdAt;
            $statut = random_int(0,1);
            $category = $tabCat[mt_rand(3,4)];
            $slug = $slugify->slugify($title);

            $publication->setTitle($title)
                ->setSlug($slug)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setCreateAt($createdAt)
                ->setUpdatedAt($updatedAt)
                ->setCategory($category)
                ->setStatut($statut);

            if ($category->getNom() === 'actualite' ) {
                $actualite = new Actualite;

                $debutPublication = $faker->dateTimeBetween('-7 days', '+3 days');
                $interval = $faker->dateTimeBetween('now')->diff($debutPublication);
                $finPublication = $faker->dateTimeBetween($interval->format('%a days'),'+4 months');
                $k = array_rand($actualiteCategory);

                $actualite->setDebutPublication($debutPublication)
                    ->setFinPublication($finPublication)
                    ->setCategory($actualiteCategory[$k])
                    ->setPublication($publication);
                
                $manager->persist($actualite);
            }

            if ($category->getNom() === 'evenement' ) {
                $evenement = new Evenement;

                $debutEvenement = $faker->dateTimeBetween('now', '+1 months');
                $interval = $faker->dateTimeBetween('now')->diff($debutEvenement);
                $finEvenement = $faker->dateTimeBetween($interval->format('%a days'),'+4 months');
                $statutEvenement = random_int(0,1);
                $subtitle = $faker->sentence(3);
                $k = array_rand($evenementCategory);

                $evenement->setDebutEvenement($debutEvenement)
                    ->setFinEvenement($finEvenement)
                    ->setStatut($statutEvenement)
                    ->setCategory($evenementCategory[$k])
                    ->setSubtitle($subtitle)
                    ->setPublication($publication);
                
                $manager->persist($evenement);
            }
            
            for($j = 1;$j <= mt_rand(1,5); $j++) {
                $lienUtileExterne = new LienUtileExterne();
                
                $url = $faker->url();
                $title = $faker->sentence();
    
                $lienUtileExterne->setUrl($url)
                    ->setTitle($title)
                    ->setPublication($publication);
                
                $manager->persist($lienUtileExterne); 
            }

            for($j = 1;$j <= mt_rand(1,5); $j++) {
                $ressource = new Ressource();
                
                $url = $faker->url();
                $updatedAt = $faker->dateTimeBetween('-6 months');
                $title = $faker->sentence();
    
                $ressource->setUrl($url)
                    ->setTitle($title)
                    ->setUpdatedAt($updatedAt)
                    ->setPublication($publication);
                
                $manager->persist($ressource); 
            }

            $manager->persist($publication);
        }

        for($i = 1;$i <= 10; $i++) {
            $galerie = new Galerie();
            
            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,350,mt_rand(1,1000));
            $description = $faker->paragraph(2);
            $slug = $slugify->slugify($title);
            $createdAt = $faker->dateTimeBetween('-6 months');
            $updatedAt = $createdAt;
            $orderBy = random_int(0,1);

            $galerie->setTitle($title)
            ->setSlug($slug)
            ->setCoverImage($coverImage)
            ->setDescription($description)
            ->setCreateAt($createdAt)
            ->setUpdatedAt($updatedAt)
            ->setOrderBy($orderBy)
            ->setStatut(true);
            
            for ($j = 1; $j <= mt_rand(100,150); $j++) {
                $image= new Image();
                
                $image->setUrl($faker->imageUrl(mt_rand(200,1000),mt_rand(200,1000),mt_rand(1,1000)))
                      ->setCaption($faker->sentence())
                      ->setGalerie($galerie);
                      
                $manager->persist($image);      
            }
            $manager->persist($galerie);
        }

        $carrousel = new Carrousel();
        $manager->persist($carrousel);

        for($i = 1;$i <= 10; $i++) {
            $carrouselObjet = new CarrouselObjet();
            $title = $faker->sentence(4);
            $introduction = $faker->sentence(12);
            $coverImage = $faker->imageUrl(1200,400,mt_rand(1,1000));
            $updatedAt = $faker->dateTimeBetween('now');

            $carrouselObjet->setTitle($title)
                           ->setIntroduction($introduction)
                           ->setCoverImage($coverImage)
                           ->setUpdatedAt($updatedAt);

            $manager->persist($carrouselObjet); 
        }

        $flashInfo = new FlashInfo();
        $manager->persist($flashInfo);

        for($i = 1;$i <= 10; $i++) {
            $flashInfoObjet = new FlashInfoObjet();
            $title = $faker->sentence(3);
            $information = $faker->sentence(10);
            $lienExterne = $faker->url();
            $choixLien = random_int(0,1);

            $flashInfoObjet->setTitle($title)
                           ->setInformation($information) 
                           ->setChoixLien($choixLien)
                           ->setLienExterne($lienExterne);

            $manager->persist($flashInfoObjet); 
        }

        $accesRapide = new AccesRapide();
        $manager->persist($accesRapide);

        for($i = 1;$i <= 10; $i++) {
            $accesRapideObjet = new AccesRapideObjet();
            $title = $faker->sentence(2);
            $icone = 'fas fa-chess-queen';
            $lien = $publication;

            $accesRapideObjet->setTitle($title)
                           ->setIcone($icone) 
                           ->setLienPublication($lien);
            
            $manager->persist($accesRapideObjet);
        }
        

        /*$mailChimp = new MailChimp('73484659907bcc82f2a5075658280960-us10');
        $list_id = '9e1618bc4a';
        $batch = $mailChimp->new_batch();
        
        for ($i = 1;$i <= 50; $i++) {
            $newsletter = new NewsletterSubscriber();
            $email = $faker->email();
            $confirmation = 1;//random_int(0,1);

            $newsletter->setEmail($email)
                       ->setConfirmation($confirmation);
           
            $manager->persist($newsletter); 
            
            $batch->post("op$i", "lists/$list_id/members", [
				'email_address' => $email,
				'status'        => 'subscribed',
			]);
        }
        $batch->execute();*/
  
        $manager->flush();
        
    }
}
