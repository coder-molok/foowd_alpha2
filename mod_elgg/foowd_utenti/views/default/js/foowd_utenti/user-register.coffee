
( (root, factory)-> 

    if typeof define is 'function' and define.amd
        # AMD. Register as an anonymous module.
        define(['elgg','jquery', 'handlebars', 'crop', 'foowdFormCheck', 'foowd_utenti/gallery-crop-lightbox', 'foowd_utenti/file'], factory);
    else if typeof exports is 'object'
        module.exports = factory();
    else
        root.returnExports = factory();
  
)(this, 


()->

    elgg = require('elgg')
    $ = require('jquery')

    # rimuovo tutti i default javascript di elgg
    $('form, form *').unbind();

    # crop = require('crop')
    # crop = require('foowdCropLightbox')
    crop = require('foowd_utenti/gallery-crop-lightbox')
    form = require('foowdFormCheck')

    Jhook = $('#offer-hook')
    Jform = Jhook.parents('form:first')
    Jgenre = $('[name=Genre]')

    # inizializzazione: il default e' standard
    # gli devo aggiungere ectype per poter mandare i files
    Jform.attr('enctype','multipart/form-data');
    Jhook.css {display: 'none'}
    Jgenre.val('standard')
    
    # rimosso in quanto non presenti immagini
    # vedere come istanziato init di crop dentro a file
    # file = require('foowd_utenti/file')  
    # crop.create().initialize(file.fileCropInit())
    # evento alla fine del caricamento
    # $( document ).on "foowd:update:file", (e, mydata)->
    #     crop.create().initialize(file.fileCropInit())
    #     return

    # scambio l'ordine di visualizzazione di username e name
    el1 = Jform.find('[name="username"]').parent();
    el2 = Jform.find('[name="name"]').parent();

    copy_to = el1.clone(true);
    copy_from = el2.clone(true);
    el2.replaceWith(copy_to);
    el1.replaceWith(copy_from);

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
    # ar.push({cls:'Text', obj:{inpt:'form.elgg-form-register input[name="name"]', key:'name', el:'form.elgg-form-register [name="name"]', msg: 'foowd:user:name:error'} })
    ar.push({cls:'Email', obj:{inpt:'form.elgg-form-register [name="email"]', key:'email', el:'form.elgg-form-register [name="email"]', msg: 'foowd:user:email:error', 'afterCheck': ajaxCheck} })
    
    # almeno di 4 lettere
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-register [name="username"]', key:'username', el:'form.elgg-form-register [name="username"]', msg: 'foowd:user:username:error', 'afterCheck': ajaxCheck} })
    
    # ar.push({cls:'Text', obj:{inpt:'form.elgg-form-register [name="password"]', key:'password', el:'form.elgg-form-register [name="password"]', msg: 'foowd:user:password:error', 'afterCheck': ajaxCheck} })
    ar.push({cls:'Phone', obj:{inpt:'form.elgg-form-register [name="Phone"]', key:'Phone', el:'form.elgg-form-register [name="Phone"]', msg: 'foowd:user:phone:error'} })
    ar.push({cls:'WebDomain', obj:{inpt:'form.elgg-form-register [name="Site"]', key:'Site', el:'form.elgg-form-register [name="Site"]', msg: 'foowd:user:site:error'} })
    ar.push({cls:'Piva', obj:{inpt:'form.elgg-form-register [name="Piva"]', key:'Piva', el:'form.elgg-form-register [name="Piva"]', msg: 'foowd:user:piva:error'} })
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-register [name="Address"]', key:'Address', el:'form.elgg-form-register [name="Address"]', msg: 'foowd:user:address:error'} })
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-register [name="Company"]', key:'Company', el:'form.elgg-form-register [name="Company"]', msg: 'foowd:user:company:error'} })
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-register [name="Owner"]', key:'Owner', el:'form.elgg-form-register [name="Owner"]', msg: 'foowd:user:owner:error'} })
    
    ar.push({cls:'Select', obj:{inpt:'form.elgg-form-register [name="City"]', key:'City', el:'form.elgg-form-register [name="City"]', msg: 'foowd:user:city:error'} })
    ar.push({cls:'Select', obj:{inpt:'form.elgg-form-register [name="AddressesType"]', key:'AddressesType', el:'form.elgg-form-register [name="AddressesType"]', msg: 'foowd:user:addressestype:error'} })
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-register [name="Civic"]', key:'Civic', el:'form.elgg-form-register [name="Civic"]', msg: 'foowd:user:civic:error'} })
    ar.push({cls:'Integer', obj:{inpt:'form.elgg-form-register [name="Zipcode"]', key:'Zipcode', el:'form.elgg-form-register [name="Zipcode"]', msg: 'foowd:user:zipcode:error', sizeL: {min: 5, max: 6} } })

    fct.pushFromArray(ar)
    # di default nessuno di questi e' obbligatorio

    needAr = ['email', 'username','name']
    noNeedAr = ['Site']
    # se l'utente e' offerente
    needOfferente = ['Phone', 'Owner', 'Piva', 'Address', 'Company', 'City', 'AddressesType', 'Civic', 'Zipcode']
    
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
        # console.log fct
    setNeed(false)


    # nascondo il campo "name" in quanto forviante
    # $('.mtm').css({'display':'none'})
    
    form.submit 'form.elgg-form-register',
        ()->

            # impongo che nome visualizzato sia il nick name
            
            Jname = $('form.elgg-form-register [name="name"]');
            userVal = $('form.elgg-form-register [name="username"]').val()
            if( Jname.val() == '' ) then Jname.val(userVal);

            # if Jgenre.val() is 'offerente'
            #     if not file.atLeastOne() then alert "Devi inserire almeno un'immagine"

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
