$('#marginHeaderGalerie').css("height",($('#headerGalerieText').height()-23));
$(window).resize(function() {
    $('#marginHeaderGalerie').css("height",($('#headerGalerieText').height()-23));
})

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
var galerieSlug = $('#galerieSlug').val();

function getPenPath() {
    if (this.loadCount == 0) {this.loadCount = 1;}
    if (this.loadCount < pageMax) {
        var page = ( this.loadCount + 1 );
        return '/galerie/'+galerieSlug+'/'+page+'/next' 
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
    });
}

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