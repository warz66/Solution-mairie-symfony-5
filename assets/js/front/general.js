/* bouton retour */

    $('#retourHaut').click(function(){
        $('html, body').animate({scrollTop: 0}, 1500); // donne un effet de défilement lisse 
    });

/* Permet de placer à la bonne distance l'elements marginHeaderRubrique par rapport à la taille de headerRubriqueText */

    $('#marginHeaderRubrique').css("height",($('#headerRubriqueText').height()-23));
    $(window).resize(function(){
            $('#marginHeaderRubrique').css("height",($('#headerRubriqueText').height()-23));
    })

/* code divers à revérifier si solution css possible ou erreur de conception */

    $(document).ready(function() {
        if (window.innerWidth < 576) { // resout goutiere bootstrap si container fluid pading 0, chercher solution css 
            $('.row').addClass('no-gutters');
        } else {
            $('.row').removeClass('no-gutters');
        }
    });
        $(window).resize(function() {
        if (window.innerWidth < 576) { // resout goutiere bootstrap si container fluid pading 0, chercher solution css 
            $('.row').addClass('no-gutters');
        } else {
            $('.row').removeClass('no-gutters');
        }
    });