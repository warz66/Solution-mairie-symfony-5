/* menu */

    var menu = document.getElementById("menu");
    var sticky = menu.offsetTop;

    window.onscroll = function() {
        menuSticky()
        // lié au bouton retour 
        document.getElementById("retourHaut").className = (window.pageYOffset > 500) ? "visible" : "invisible";
    };
    
    function menuSticky() {
        if (window.pageYOffset > sticky && window.innerWidth >= 768) { // à test window.innerWidth
            if (!menu.classList.contains("fixed-top")) {
                menu.classList.add("fixed-top");
                menu.classList.add("bg-light");
                menu.classList.remove("container-xl");
                let paddingTop = $('#menu').height()+parseInt($('#menu').css('margin-bottom'))+parseInt($('#menu').css('margin-top'));
                /*$('.fixed-top').next().css('padding-top', paddingTop);*/
                $('.fixed-top').next().attr('style', 'padding-top: '+paddingTop+'px !important');
            }
        } else {
            /*$('.fixed-top').next().css('padding-top', 0);*/
            $('.fixed-top').next().attr('style', 'padding-top: 0px !important');
            menu.classList.remove("fixed-top");
            menu.classList.remove("bg-light");
            menu.classList.add("container-xl");
        }
    }

/* infos mairie */

    $("#cercle3design").click(function() {
        $('#infosMairie').slideToggle('fast', function() {
            // on recalcul la position du menu
            if (window.innerWidth >= 768) {
                sticky = menu.offsetTop;
            }
        });
        if ($('#buttonHamburger').hasClass('is-active') && $('#infosMairie').is(':visible')) {
            $('#cercle3design').toggle('fast');
        }
    });

/* barre de recherche */

    $("#searchBarClose").click(function() {
        $("#searchBarInput").focusout();
        $('#searchBar').toggle('fast');
    });

    $("#searchPop").click(function() { 
        $('#searchBar').toggle('fast');  
        //$("#searchBarInput").focus();    
    });

/* menu hamburger */

    $("#buttonHamburger").click(function() { 
        $("#buttonHamburger").toggleClass("is-active");
        $('#menu').slideToggle('fast');
        $('#searchBar').css('display','none');
        if (!$('#infosMairie').is(':visible')) {
            $('#cercle3design').toggle('fast');     
        }
    });

    $(window).resize(function() {
        if (window.innerWidth >= 768 && $('#buttonHamburger').hasClass('is-active')) { // résout un effet indésirable si on resize la fenêtre sur la position du menu en position fixed
            $('#buttonHamburger').click();
        }
        if (window.innerWidth >= 768 && $('#infosMairie').is(':visible')) {
            sticky = document.getElementById("infosMairie").offsetTop + document.getElementById("infosMairie").offsetHeight;
        }
        if (window.innerWidth >= 768 && $('#infosMairie').is(':hidden')) {
            sticky = $('#logoHeader').outerHeight(true);
        }
    });