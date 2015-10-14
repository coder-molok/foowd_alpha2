
( (root, factory)-> 

    if typeof define is 'function' and define.amd
        # AMD. Register as an anonymous module.
        define(['elgg','jquery', 'crop','foowd_utenti/file' ,'foowdCropLightbox', 'foowd_utenti/file'], factory);
    else if typeof exports is 'object'
        module.exports = factory();
    else
        root.returnExports = factory();
  
)(this, 


()->

    elgg = require('elgg')
    $ = require('jquery')
    # crop = require('crop')
    crop = require('foowdCropLightbox')

    Jhook = $('#offer-hook')
    Jform = Jhook.parents('form:first')


    # inizializzazione: il default e' standard
    # gli devo aggiungere ectype per poter mandare i files
    Jform.attr('enctype','multipart/form-data');

    
    # vedere come istanziato init di crop dentro a file
    file = require('foowd_utenti/file')  
    crop.create().initialize(file.fileCropInit())
    # evento alla fine del caricamento
    $( document ).on "foowd:update:file", (e, mydata)->
        crop.create().initialize(file.fileCropInit())
        return



    $('form.elgg-form-avatar').submit  (e)->

            alert 'lol'
            e.preventDefault()

            return true
     

);
