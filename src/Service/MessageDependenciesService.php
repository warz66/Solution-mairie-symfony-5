<?php 

namespace App\Service;

use App\Repository\CarrouselObjetRepository;
use App\Repository\FlashInfoObjetRepository;
use App\Repository\AccesRapideObjetRepository;
use App\Repository\LienUtileInterneRepository;

/**
 * Gestion des messages de validation lors de la dépublication/supression de contenu.
 */
class MessageDependenciesService {

    private $repoLienInternes;
    private $repoAccesRapideObjet;
    private $repoFlashInfoObjet;
    private $repoCarouselObjet;

    public function __construct(LienUtileInterneRepository $repoLienInternes, AccesRapideObjetRepository $repoAccesRapideObjet, FlashInfoObjetRepository $repoFlashInfoObjet, CarrouselObjetRepository $repoCarouselObjet) {
        $this->repoLienInternes = $repoLienInternes;
        $this->repoAccesRapideObjet = $repoAccesRapideObjet;
        $this->repoFlashInfoObjet = $repoFlashInfoObjet;
        $this->repoCarouselObjet = $repoCarouselObjet;
    }

    /**
     * Function qui boucle sur tous les objets d'une page d'index (page actu event) ) afin de creer pour chacun de ces objets un message qui signal quels entités dependent de lui.
     *
     * @param [array] $publications
     * @return array
     */
    public function getMsgPublicationsDependencies($publications) {
        $publicationsWithDependencies = array();
        $liensInternes = $this->repoLienInternes->findPublications($publications);
        $accesRapideObjets = $this->repoAccesRapideObjet->findPublications($publications);
        $flashInfoObjets = $this->repoFlashInfoObjet->findPublications($publications);
        $carouselObjets = $this->repoCarouselObjet->findPublications($publications);
        foreach($publications as $publication) {
            // liens internes
            foreach($liensInternes as $lienInterne) {
                if ($lienInterne->getLienPublication() === $publication) {
                    if (!array_key_exists($publication->getId(), $publicationsWithDependencies)) { 
                        $publicationsWithDependencies[$publication->getId()]= ""; 
                    }
                    $publicationsWithDependencies[$publication->getId()] = $publicationsWithDependencies[$publication->getId()]."<br>-Lien utile interne dans la publication({$lienInterne->getPublication()->getCategory()->getNom()})  : <strong>{$lienInterne->getPublication()->getTitle()}</strong>";
                }
            }    
            //  acces rapide
            foreach($accesRapideObjets as $accesRapideObjet) {
                if ($accesRapideObjet->getLienPublication() === $publication) {
                    if (!array_key_exists($publication->getId(), $publicationsWithDependencies)) { 
                        $publicationsWithDependencies[$publication->getId()]= ""; 
                    }
                    $publicationsWithDependencies[$publication->getId()] = $publicationsWithDependencies[$publication->getId()]."<br>-L'objet de l'accès rapide : <strong>{$accesRapideObjet->getTitle()}</strong>";
                }
            }    
            //  flash info
            foreach($flashInfoObjets as $flashInfoObjet) {
                if ($flashInfoObjet->getLienInterne() === $publication) {
                    if (!array_key_exists($publication->getId(), $publicationsWithDependencies)) { 
                        $publicationsWithDependencies[$publication->getId()]= ""; 
                    }
                    $publicationsWithDependencies[$publication->getId()] = $publicationsWithDependencies[$publication->getId()]."<br>-L'objet du flash info : <strong>{$flashInfoObjet->getTitle()}</strong>";
                }
            }    
            //  carousel
            foreach($carouselObjets as $carouselObjet) {
                if ($carouselObjet->getLienPublication() === $publication) {
                    if (!array_key_exists($publication->getId(), $publicationsWithDependencies)) { 
                        $publicationsWithDependencies[$publication->getId()]= ""; 
                    }
                    $publicationsWithDependencies[$publication->getId()] = $publicationsWithDependencies[$publication->getId()]."<br>-L'objet du carousel : <strong>{$carouselObjet->getTitle()}</strong>";
                }
            }    
        }
        return $publicationsWithDependencies;
    }

    /**
     * Function qui boucle sur chaque rubrique d'un index afin de creer pour chacun de ces objets un message qui signal quels entités dependent de lui.
     *
     * @param [type] $publications
     * @return array
     */
    public function getMsgRubriquesDependencies($rubriques) {
        $rubriquesWithDependencies = array();
        $liensInternes = $this->repoLienInternes->findPublications($rubriques);
        foreach($rubriques as $rubrique) {
            // enfants
            foreach($rubrique->getEnfants() as $enfant) {
                if (!array_key_exists($rubrique->getId(), $rubriquesWithDependencies)) { 
                    $rubriquesWithDependencies[$rubrique->getId()]= ""; 
                }
                $rubriquesWithDependencies[$rubrique->getId()] = $rubriquesWithDependencies[$rubrique->getId()]."<br>-Parent de la publication({$enfant->getCategory()->getNom()})  : <strong>{$enfant->getTitle()}</strong>";    
            }
            // liens internes
            foreach($liensInternes as $lienInterne) {
                if ($lienInterne->getLienPublication() === $rubrique) {
                    if (!array_key_exists($rubrique->getId(), $rubriquesWithDependencies)) { 
                        $rubriquesWithDependencies[$rubrique->getId()]= ""; 
                    }
                    $rubriquesWithDependencies[$rubrique->getId()] = $rubriquesWithDependencies[$rubrique->getId()]."<br>-Lien utile interne dans la publication({$lienInterne->getPublication()->getCategory()->getNom()})  : <strong>{$lienInterne->getPublication()->getTitle()}</strong>";
                }
            }
        }
        return $rubriquesWithDependencies;
    }

    /**
     * Function qui boucle sur chaque sous-rubrique d'un index afin de creer pour chacun de ces objets un message qui signal quels entités dependent de lui.
     *
     * @param [type] $publications
     * @return array
     */
    public function getMsgSousRubriquesDependencies($sousRubriques) {
        $sousRubriquesWithDependencies = array();
        $accesRapideObjets = $this->repoAccesRapideObjet->findPublications($sousRubriques);
        $liensInternes = $this->repoLienInternes->findPublications($sousRubriques);
        foreach($sousRubriques as $sousRubrique) {
            // enfants
            foreach($sousRubrique->getEnfants() as $enfant) {
                if (!array_key_exists($sousRubrique->getId(), $sousRubriquesWithDependencies)) { 
                    $sousRubriquesWithDependencies[$sousRubrique->getId()]= ""; 
                }
                $sousRubriquesWithDependencies[$sousRubrique->getId()] = $sousRubriquesWithDependencies[$sousRubrique->getId()]."<br>-Parent de la page  : <strong>{$enfant->getTitle()}</strong>";    
            }
            // liens internes
            foreach($liensInternes as $lienInterne) {
                if ($lienInterne->getLienPublication() === $sousRubrique) {
                    if (!array_key_exists($sousRubrique->getId(), $sousRubriquesWithDependencies)) { 
                        $sousRubriquesWithDependencies[$sousRubrique->getId()]= ""; 
                    }
                    $sousRubriquesWithDependencies[$sousRubrique->getId()] = $sousRubriquesWithDependencies[$sousRubrique->getId()]."<br>-Lien utile interne dans la publication({$lienInterne->getPublication()->getCategory()->getNom()})  : <strong>{$lienInterne->getPublication()->getTitle()}</strong>";
                }
            }
            //  acces rapide
            foreach($accesRapideObjets as $accesRapideObjet) {
                if ($accesRapideObjet->getLienPublication() === $sousRubrique) {
                    if (!array_key_exists($sousRubrique->getId(), $sousRubriquesWithDependencies)) { 
                        $sousRubriquesWithDependencies[$sousRubrique->getId()]= ""; 
                    }
                    $sousRubriquesWithDependencies[$sousRubrique->getId()] = $sousRubriquesWithDependencies[$sousRubrique->getId()]."<br>-L'objet de l'accès rapide : <strong>{$accesRapideObjet->getTitle()}</strong>";
                }
            } 
        }
        return $sousRubriquesWithDependencies;
    }

    /**
     * On retourne le message des dépendances d'une publication.
     *
     * @param [publication] $publications
     * @return string
     */
    public function getMsgPublicationDependencies($publication) {
        $publicationWithDependencies = "";
        if ($publication->getId() !== null) { // on gere le cas ou l'on est pas ds une création d'une publication
            $liensInternes = $this->repoLienInternes->findPublications($publication);
            $accesRapideObjets = $this->repoAccesRapideObjet->findPublications($publication);
            $flashInfoObjets = $this->repoFlashInfoObjet->findPublications($publication);
            $carouselObjets = $this->repoCarouselObjet->findPublications($publication);
                // liens internes
                foreach($liensInternes as $lienInterne) {
                    $publicationWithDependencies = $publicationWithDependencies ."<br>-Lien utile interne dans la publication({$lienInterne->getPublication()->getCategory()->getNom()})  : <strong>{$lienInterne->getPublication()->getTitle()}</strong>";
                }    
                //  acces rapide
                foreach($accesRapideObjets as $accesRapideObjet) {
                    $publicationWithDependencies = $publicationWithDependencies ."<br>-L'objet de l'accès rapide : <strong>{$accesRapideObjet->getTitle()}</strong>";
                }    
                //  flash info
                foreach($flashInfoObjets as $flashInfoObjet) {
                    $publicationWithDependencies = $publicationWithDependencies ."<br>-L'objet du flash info : <strong>{$flashInfoObjet->getTitle()}</strong>";
                }    
                //  carousel
                foreach($carouselObjets as $carouselObjet) {
                    $publicationWithDependencies = $publicationWithDependencies ."<br>-L'objet du carousel : <strong>{$carouselObjet->getTitle()}</strong>";
                }
            }    
        return $publicationWithDependencies;
    }

    public function getMsgGaleriesDependencies($galeries) {
        $galerieWithDependencies = array();
        foreach($galeries as $galerie) {
            $publications = $galerie->getPublications()->getValues();
            foreach($publications as $publication) {
                if (!array_key_exists($galerie->getId(), $galerieWithDependencies)) { 
                    $galerieWithDependencies[$galerie->getId()]= ""; 
                }
                $galerieWithDependencies[$galerie->getId()] = $galerieWithDependencies[$galerie->getId()]."<br>-La publication({$publication->getCategory()->getNom()})  : <strong>{$publication->getTitle()}</strong>";        
            }  
        }
        return $galerieWithDependencies;   
    }

    public function getMsgGalerieDependencies($galerie) {
        $galerieWithDependencies = "";
        $publications = $galerie->getPublications()->getValues();
        foreach($publications as $publication) {
            $galerieWithDependencies = $galerieWithDependencies . "<br>-La publication({$publication->getCategory()->getNom()})  : <strong>{$publication->getTitle()}</strong>";        
        }  
        return $galerieWithDependencies;   
    }
    
}