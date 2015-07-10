
( (root, factory)-> 

    if typeof define is 'function' and define.amd
        # AMD. Register as an anonymous module.
        define(['elgg','jquery', 'handlebars', 'crop', 'foowdFormCheck', 'foowdCropLightbox'], factory);
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
    form = require('foowdFormCheck')

    Jhook = $('#offer-hook')
    Jform = Jhook.parents('form:first')
    Jgenre = $('[name=Genre]')

    # inizializzazione: il default e' standard
    # gli devo aggiungere ectype per poter mandare i files
    Jform.attr('enctype','multipart/form-data');
    Jhook.css {display: 'none'}
    Jgenre.val('standard')


    init2 =
        # ricavo l'url
        urlF : document.getElementById('url').href
        # l'id del campo input che immagazzina i file, ovvero le immagini
        fileInput : '[name="file1"]'
        css: [
            'mod/foowd_utility/js/imgareaselect/css/imgareaselect-default.css',
            'mod/foowd_utility/js/foowd-crop/foowd-crop.css'
        ]
        # deve esistere, e li dentro verra' immagazzinata l'immagine, se gia' non esiste
        loadedImgContainer : '#file1-container'

        
        # l'id del tag img che funge da sorgente. Se esiste lo carica in $img privata del plugin, altrimenti lo creera' col caricamento dell'immagine
        sourceImg: '#file1-sorgente'
        # id del box che contiene tutte le immagini: appeso dopo $init.fileInput
        imgContainer:'#file1-image-container',
        imgAreaPrefix: 'file1'
    
    crop.create().initialize(init2)

    ## parto con l'impostare i parametri di crop
    #  NB: tutti gli ID sono impostati senza il query selector, ovvero con solo il nome puro
    init =
        # ricavo l'url
        urlF : document.getElementById('url').href
        # l'id del campo input che immagazzina i file, ovvero le immagini
        fileInput : '[name="file2"]'
        css: [
            'mod/foowd_utility/js/imgareaselect/css/imgareaselect-default.css',
            'mod/foowd_utility/js/foowd-crop/foowd-crop.css'
        ]
        # deve esistere, e li dentro verra' immagazzinata l'immagine, se gia' non esiste
        loadedImgContainer : '#file2-container'
        imgAreaPrefix: 'file2'

        
        # l'id del tag img che funge da sorgente. Se esiste lo carica in $img privata del plugin, altrimenti lo creera' col caricamento dell'immagine
        sourceImg: '#file2-sorgente'
        # id del box che contiene tutte le immagini: appeso dopo $init.fileInput
        imgContainer:'#file2-image-container'
    
    crop.create().initialize(init)

    test = new Text()
    
    # for each input
    fct = form.factory();
    ar = []

    # preimpostati
    # fielsd
    flds = ['email', 'username', 'name' , 'password']
    for va in flds
        JmailLabel = $('[name="'+va+'"]').prevUntil('','label');
        JmailLabel.attr({'for': va})

    # $('<span/>',{'html':'**','class':'extra-Site'}).appendTo($('label[for="Site"]'))
    


    ajaxCheck = ()->

        v = @el.val().trim()
        url=elgg.get_site_url()+'foowd_utility/user-check?'+@key+'='+v
        # console.log v
        elgg.get(url, {
            success: (resultText, success, xhr)=>
                obj = JSON.parse(resultText)
                if typeof obj is 'object'
                    # console.log obj
                    ret = obj[@key]
                    # console.log ret
                else
                    ret= false

                if ret
                    @error('Qesto valore e\' gia\' utilizzato. Prova con un altro')
                    ret = false
                else
                    ret = true

                @status = ret
        });



    # NB: il campo "name" non e' univoco: utenti differenti possono avere lo stesso Name
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-register input[name="name"]', key:'name', el:'form.elgg-form-register [name="name"]', msg: 'foowd:user:name:error'} })
    ar.push({cls:'Email', obj:{inpt:'form.elgg-form-register [name="email"]', key:'email', el:'form.elgg-form-register [name="email"]', msg: 'foowd:user:email:error', 'afterCheck': ajaxCheck} })
    
    # almeno di 4 lettere
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-register [name="username"]', key:'username', el:'form.elgg-form-register [name="username"]', msg: 'foowd:user:username:error', 'afterCheck': ajaxCheck} })
    
    # ar.push({cls:'Text', obj:{inpt:'form.elgg-form-register [name="password"]', key:'password', el:'form.elgg-form-register [name="password"]', msg: 'foowd:user:password:error', 'afterCheck': ajaxCheck} })
    ar.push({cls:'Phone', obj:{inpt:'form.elgg-form-register [name="Phone"]', key:'Phone', el:'form.elgg-form-register [name="Phone"]', msg: 'foowd:user:phone:error'} })
    ar.push({cls:'WebDomain', obj:{inpt:'form.elgg-form-register [name="Site"]', key:'Site', el:'form.elgg-form-register [name="Site"]', msg: 'foowd:user:site:error'} })
    ar.push({cls:'Piva', obj:{inpt:'form.elgg-form-register [name="Piva"]', key:'Piva', el:'form.elgg-form-register [name="Piva"]', msg: 'foowd:user:piva:error'} })
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-register [name="Address"]', key:'Address', el:'form.elgg-form-register [name="Address"]', msg: 'foowd:user:address:error'} })
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-register [name="Company"]', key:'Company', el:'form.elgg-form-register [name="Company"]', msg: 'foowd:user:company:error'} })
    fct.pushFromArray(ar)
    # di default nessuno di questi e' obbligatorio

    needAr = ['email', 'username','name']
    noNeedAr = ['Site']
    setNeed = (bool)->
        fct.each( ()->
            if (@key in needAr) 
                    @needle = true
            else if (@key in noNeedAr)
                    @needle = false
            else
                @needle = bool 
                
            return
            )
    setNeed(false)


    
    form.submit 'form.elgg-form-register',
        ()->
            pwd = $('form.elgg-form-register [name="password"]').val()
            pwd2 = $('form.elgg-form-register [name="password2"]').val()
            
            if pwd.length <= 5  
                alert "La password deve contentere almeno 6 caratteri"
                return false

            if pwd isnt pwd2 
                alert "Attenzione, le password non combaciano"
                return false

            return true

    


    Jgenre.on "change", ()->
        if $(this).val() is 'offerente'
            Jhook.fadeIn('slow')
            setNeed(true)
        else
            Jhook.fadeOut('slow')
            setNeed(false)
            # procedura di azzeramento del form extra
            $('#offer-hook').find('[type="text"]').each(
                ()->
                    $(this).val('')
            )

    

);
