<?php 

namespace App\Service;

use App\Entity\Publication;

/**
 * Gestion des des liens utiles pour le front
 * 
 */
class LiensUtilesService {

    public function make(Publication $publication) {

        $liensUtilesInternes = array();
        if (!$publication->getLiensUtilesInternes()->isEmpty()) {
            foreach($publication->getLiensUtilesInternes()->getValues() as $key => $lienUtileInterne) {
                if ($lienUtileInterne->getLienPublication()->getStatut() && !$lienUtileInterne->getLienPublication()->getTrash()) { // à tester
                    $liensUtilesInternes[$key]['title'] = $lienUtileInterne->getLienPublication()->getTitle();
                    $liensUtilesInternes[$key]['url'] = '/publication/'.$lienUtileInterne->getLienPublication()->getSlug();
                }
            }
        }
        $liensUtilesExternes = array();
        if (!$publication->getLiensUtilesExternes()->isEmpty()) {
            foreach($publication->getLiensUtilesExternes()->getValues() as $key => $lienUtileExterne) {
                $liensUtilesExternes[$key]['title'] = $lienUtileExterne->getTitle();
                $liensUtilesExternes[$key]['url'] = $lienUtileExterne->getUrl();
            }
        }

        $liensUtiles = array_merge($liensUtilesInternes, $liensUtilesExternes);

        return $liensUtiles;
    }
}