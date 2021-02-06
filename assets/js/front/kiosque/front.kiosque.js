var $grid = $('.grid').masonry({
    itemSelector: 'none',
    columnWidth: '.grid-item',
    gutter: 30,
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

function getPenPath() {
    if (this.loadCount == 0) {this.loadCount = 1;}
    if (this.loadCount < pageMax) {
        var page = ( this.loadCount + 1 );
        return '/kiosque/'+page+'/next' 
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