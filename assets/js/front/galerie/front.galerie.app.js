var jQueryBridget = require('jquery-bridget');

var Masonry = require('masonry-layout');
jQueryBridget( 'masonry', Masonry, $ );

var InfiniteScroll = require('infinite-scroll');
jQueryBridget( 'infiniteScroll', InfiniteScroll, $ );

var imagesLoaded = require('imagesloaded');
InfiniteScroll.imagesLoaded = imagesLoaded;

require("@fancyapps/fancybox");

require('./front.galerie.js');