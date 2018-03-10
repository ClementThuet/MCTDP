// webpack.config.js
var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where all compiled assets will be stored
    .setOutputPath('public/build/')

    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')

    // will create public/build/app.js and public/build/app.css
    .addEntry('rep_log', './public/assets/js/RepLogApp.js')
    
     .addEntry('rep_log', './public/assets/js/RepLogApp.js')
    //.enableBuildNotifications()
    
    .autoProvidejQuery()
;

// export the final configuration
module.exports = Encore.getWebpackConfig();