/* bouton retour */

    $('#retourHaut').click(function(){
        $('html, body').animate({scrollTop: 0}, 1500); // donne un effet de défilement lisse 
    });

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