var Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */


     
    /****** Front ******/

    /****** Front Base CSS ******/ 
    .addStyleEntry('css/front.app', './assets/css/front/front.app.css')

    /***** Front Home CSS *****/
    .addStyleEntry('css/front.home.app', './assets/css/front/home/front.home.app.css')

    /****** Front Kiosque CSS ******/ 
    .addStyleEntry('css/front.kiosque', './assets/css/front/kiosque/front.kiosque.css')

    /****** Front Galerie && Galeries CSS ******/ 
    .addStyleEntry('css/front.galerie.app', './assets/css/front/galerie/front.galerie.app.css')
    .addStyleEntry('css/front.galeries', './assets/css/front/galerie/front.galeries.css')

    /****** Front Actualite && Actualites CSS ******/ 
    .addStyleEntry('css/front.publication.actualite', './assets/css/front/publication/actualite/front.publication.actualite.css')
    .addStyleEntry('css/front.publication.actualites', './assets/css/front/publication/actualite/front.publication.actualites.css')

    /****** Front Evenement && Evenements CSS ******/ 
    .addStyleEntry('css/front.publication.evenement', './assets/css/front/publication/evenement/front.publication.evenement.css')
    .addStyleEntry('css/front.publication.evenements', './assets/css/front/publication/evenement/front.publication.evenements.css')

    /****** Front Page CSS ******/ 
    .addStyleEntry('css/front.publication.page', './assets/css/front/publication/page/front.publication.page.css')

    /****** Front Recherche CSS ******/ 
    .addStyleEntry('css/front.publication.recherche', './assets/css/front/publication/recherche/front.publication.recherche.css')

    /****** Front Rubrique CSS ******/ 
    .addStyleEntry('css/front.publication.rubrique', './assets/css/front/publication/rubrique/front.publication.rubrique.css')


    /****** Front Base JS ******/ 
    .addEntry('js/front.app', './assets/js/front/front.app.js')

    /****** Front Home JS ******/ 
    /*.addEntry('js/front.home.app', './assets/js/front/home/front.home.app.js')*/

    /****** Front Kiosque JS ******/ 
    .addEntry('js/front.kiosque.app', './assets/js/front/kiosque/front.kiosque.app.js')

    /****** Front Galerie JS ******/ 
    .addEntry('js/front.galerie.app', './assets/js/front/galerie/front.galerie.app.js')


    
    /****** Back ******/
     
    /****** Admin Base CSS ******/ 
    .addStyleEntry('css/admin.app', './assets/css/admin/admin.app.css')

    /****** Admin Galerie  CSS ******/
    .addStyleEntry('css/admin.galerie.edit.app', './assets/css/admin/galerie/edit/admin.galerie.edit.app.css')
    .addStyleEntry('css/admin.galerie.new', './assets/css/admin/galerie/new/admin.galerie.new.css')
    .addStyleEntry('css/admin.galerie.index', './assets/css/admin/galerie/index/admin.galerie.index.css')

    /****** Admin Base JS ******/ 
    .addEntry('js/admin.app', './assets/js/admin/admin.app.js')

    /****** Admin Galerie JS ******/
    .addEntry('js/admin.galerie.edit.app', './assets/js/admin/galerie/edit/admin.galerie.edit.app.js')
    .addEntry('js/admin.galerie.new', './assets/js/admin/galerie/new/admin.galerie.new.js')
    .addEntry('js/admin.galerie.index.app', './assets/js/admin/galerie/index/admin.galerie.index.app.js')

    //.addEntry('app', './assets/js/app.js')
    //.addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    //.splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()
    //.disableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .enablePostCssLoader()

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .configureTerserPlugin((options) => {
        options.cache = true;
        options.terserOptions = {
            output: {
                comments: false
            }
        }
    })

    .configureOptimizeCssPlugin((options) => {
        options.cssProcessor = require('cssnano');
        options.cssProcessorPluginOptions = {
            preset: ['default', { discardComments: { removeAll: true } }],
        }
    })

    /*.copyFiles([
        {from: './node_modules/ckeditor/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false},
        {from: './node_modules/ckeditor/adapters', to: 'ckeditor/adapters/[path][name].[ext]'},
        {from: './node_modules/ckeditor/lang', to: 'ckeditor/lang/[path][name].[ext]'},
        {from: './node_modules/ckeditor/plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
        {from: './node_modules/ckeditor/skins', to: 'ckeditor/skins/[path][name].[ext]'}
    ])*/

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')
;

module.exports = Encore.getWebpackConfig();
