var $grid = $('.grid').masonry({
    itemSelector: 'none',
    columnWidth: '.grid-item',
    gutter: 15,
    percentPosition: true,
    /*transitionDuration: 0,*/
    /*stagger: 50,*/
    // nicer reveal transition
    visibleStyle: { transform: 'translateY(0)', opacity: 1 },
    hiddenStyle: { transform: 'translateY(100px)', opacity: 0 },
});

// get Masonry instance
var msnry = $grid.data('masonry');

// initial items reveal
$grid.imagesLoaded( function() {
    $grid.removeClass('are-images-unloaded');
    $grid.masonry( 'option', { itemSelector: '.grid-item' });
    var $items = $grid.find('.grid-item');
    $grid.masonry( 'appended', $items );
});

var pageMax = $('#pageMax').val();
var galerieId = $('#galerieId').val();

function getPenPath() {
    if (this.loadCount == 0) {this.loadCount = 1;}
    if (this.loadCount < pageMax) {
        var page = ( this.loadCount + 1 );
        return '/admin/galerie/'+galerieId+'/'+page+'/edit/next' 
    }
}

// init Infinte Scroll
if (pageMax>1) { 
    $grid.infiniteScroll({
        path: getPenPath,
        append: '.grid-item',
        outlayer: msnry,
        status: '.page-load-status',
        history: false,
        onInit: function() {
            this.on('append', function() {
                $('i[data-action="delete"]').off('click');
                handleStatutImg();
            });
        }
    });
}

$('#galerie_imageFile_file').next().addClass('font-italic');

$('#galerie_imageFile_file').on('change', function(event) {
    var inputFile = event.currentTarget;
    if (inputFile.files.length == 1) {
        $(this).next().html(inputFile.files[0].name);
    } else if ( inputFile.files.length == 0) {
        $(this).next().html("Veuillez choisir une image de présentation");
    }
});

$('#uploadFile').on('change', function(event) {
    var files = $('#uploadFile').prop('files');
    var inputFile = event.currentTarget;
    var imgError=[];
    var i=0;
    $.each(files, function() {
        if ((this['size']>=500000) || ((this['type'] != 'image/jpeg') && (this['type'] != 'image/png'))) {
            imgError[i]=this['name'];
            i++;
        }
    });
    if (imgError.length == 0) {
        $('#inputFilesError').hide();
        $('#inputFilesError').html("");
        $('#submit').prop('disabled', false);
        // Input cover image de la galerie, permet de remplir le texte à l'interieure de l'input lors de la sélection d'un fichié.
        if (inputFile.files.length == 1) {
            filesName = inputFile.files[0].name;
        } else {
            filesName = inputFile.files.length+ ' images';
        }
        $('#uploadFileLabel').html(filesName);
    } else {
        $('#uploadFileLabel').html('Attention, format ou taille de fichier invalide');
        var textError = 'Formats acceptés : jpeg/png | Taille < 500ko<br>Erreurs sur les fichiers :<br>'
        for (var i=0;i<imgError.length;i++) {
            textError = textError+imgError[i]+', '
        }
        $('#inputFilesError').html(textError);
        $('#inputFilesError').show();
        $('#submit').prop('disabled', true);
    }
});

// Permet de manipuler l'apparence des image a supprimer, et de changer "le statut" de celle que l'on veut supprimer lors du submit
function handleStatutImg() {
    $('i[data-action="delete"]').click(function() {
        const target = this.dataset.target;
        $(this).toggleClass("far fas").toggleClass("fa-trash-alt fa-undo");
        $(target).children().find('.captionStatus').toggleClass("delete");
        if ($(this).hasClass('fa-trash-alt')) {
            $(target+' *').css({'opacity':'1','transition':'opacity 0.5s'});
        } else {
            $(target+' :not(div, i[data-action="delete"])').css({'opacity':'0.2','transition':'opacity 0.5s'});  
        }
    });
}
handleStatutImg();

// Supprime du dom les images avec le status delete avant de soumettre le formulaire
function deleteImgDomBeforeSubmit() {
    $("form[name='galerie']").submit(function() {
        $('#cancel').prop('disabled', true);
        $('.captionStatus').each(function(){
            if($(this).hasClass('delete')) {
                $(this).val($(this).val().replace(/^./, '1'));
            }
        });
        $('#b-e-default').hide();
        $('#b-e-loading').show();
        $("input[name='firstImg']").val($(".grid").children(":first").data("id"));
        $("input[name='lastImg']").val($(".grid").children(":last").data("id"));
    });
}
deleteImgDomBeforeSubmit();

// réinitialise la page en la rechargeant sans enregistrer les modifications apporté
$('#cancel').on('click', function() {
    location.reload();
});

// supprime le lien href de vichupload
$(".vich-image a").removeAttr("href");

// fait apparaître le bouton de retour top à une certaine distance en pixel de la page courante par rapport au top
window.onscroll = function() {
    document.getElementById("cRetour").className = (window.pageYOffset > 100) ? "cVisible" : "cInvisible";
};

// donne un effet de défilement lisse 
$('a#cRetour').click(function(){
    $('html, body').animate({scrollTop: 0}, 1500);
});  

// lightbox fancybox
$().fancybox({
    selector:'[data-fancybox="images"]',
    loop: false,
    beforeShow: function(instance, current) {
        // When we reach the last item in current Fancybox instance, load more images with Infinite Scroll and append them to Fancybox 
        if (current.index === instance.group.length - 1) { // 1. Check if at end of group
            // 2. Trigger infinite scroll to load next set of images
            $grid.infiniteScroll('loadNextPage');
            // 3. Get the newly loaded set of images
            var norepeat = true;
            $grid.on( 'load.infiniteScroll', function( event, response, path ) {
                if (norepeat) {
                    var $posts = $(response).find('.grid-item');
                    // 4. Set up an array to put them in
                    var newImages = [];
                    $($posts).each( function( index, element ){
                        // 5. Construct the objects
                        var a = {};
                        a['type'] = 'image';
                        a['src'] = $(this).find('a').attr('href');
                        a['opts'] = {'caption' : $(this).find('a').attr('data-caption') }
                        // 6. Add them to the array
                        newImages.push(a);
                    });
                    // 7. And append to this instance
                    instance.addContent(newImages);
                    norepeat = false;
                }
            });
        }
    }
});

$( document ).ready(function() {
    $('#submit').prop('disabled', false);
});