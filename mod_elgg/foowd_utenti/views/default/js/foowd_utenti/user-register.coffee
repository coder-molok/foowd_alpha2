
( (root, factory)-> 

    if typeof define is 'function' and define.amd
        # AMD. Register as an anonymous module.
        define(['elgg','jquery', 'handlebars', 'crop'], factory);
    else if typeof exports is 'object'
        module.exports = factory();
    else
        root.returnExports = factory();
  
)(this, 


()->

    elgg = require('elgg')
    $ = require('jquery')
    crop = require('crop')

    Jhook = $('#offer-hook')
    Jform = Jhook.parents('form:first')
    Jgenre = $('[name=Genre]')

    # inizializzazione: il default e' standard
    # gli devo aggiungere ectype per poter mandare i files
    Jform.attr('enctype','multipart/form-data');
    Jhook.css {display: 'none'}
    Jgenre.val('standard')

    Jgenre.on "change", ()->
        if $(this).val() is 'offerente'
            Jhook.fadeIn('slow')
        else
            Jhook.fadeOut('slow')


    ## parto con l'impostare i parametri di crop
    #  NB: tutti gli ID sono impostati senza il query selector, ovvero con solo il nome puro
    init =
        # ricavo l'url
        urlF : document.getElementById('url').href
        # deve esistere, e li dentro verra' immagazzinata l'immagine, se gia' non esiste
        loadedImageContainerId : 'image'
        # l'id del tag img che funge da sorgente
        sourceId : 'sorgente'
        # l'id del campo input che immagazzina i file, ovvero le immagini
        fileId : 'loadedFile'
        # id del box che contiene tutte le immagini: appeso dopo $init.fileId
        imageContainerId :'image-container'
        css: [
            'mod/foowd_utility/js/imgareaselect/css/imgareaselect-default.css',
            'mod/foowd_utility/js/foowd-crop/foowd-crop.css'
        ]
    
    crop.initialize(init)

);
