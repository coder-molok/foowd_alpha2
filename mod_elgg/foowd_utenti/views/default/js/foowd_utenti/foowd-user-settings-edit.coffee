
( (root, factory)-> 

    if typeof define is 'function' and define.amd
        # AMD. Register as an anonymous module.
        define(['elgg','jquery', 'foowdFormCheck'], factory);
    else if typeof exports is 'object'
        module.exports = factory();
    else
        root.returnExports = factory();
  
)(this, 


()->


    elgg = require('elgg')
    $ = require('jquery')
    # crop = require('crop')
    
    Jform = $('.elgg-form-usersettings-save');


    ################## PARTE ADIBITA ALLA MANIPOLAZIONE INTERFACCIA ########################
    
    # di default e' nascosto, pertanto lo faccio apparire:
    
    Jform.fadeIn('slow')


    # nascondo dei campi dalla visualizzazione: volendo posso eliminarli con javascript
    # provvedo a estrarre i campi
    $('.elgg-body').each ()->
        html = $(this).html();
        mod = $(this).closest('.elgg-module');
        $(html).insertAfter(mod);
        mod.remove();


    # visualizzo il campo username al posto del nome, ma lo rendo non modificabile
    $('<label for="name">Nome Visualizzato</label>').insertBefore($('input[name="name"]')); #.attr('disabled', true));
    $('[for="name"], [name="name"]').wrapAll('<div></div>');

    $('<label for="email">Email</label>').insertBefore($('input[name="email"]'));#.attr('disabled', true));
    $('[for="email"], [name="email"]').wrapAll('<div></div>');

    # nascondo alcuni campi
    $('p input[name*="password"]').closest('p').css('display', 'none');
    $('select[name="language"]').css({'display':'none'});
    $('input[name="method[email]"]').closest('table').css({'display':'none'});


    genre = ($('[name="js_admin"]').val() == 'amministratore');
    if(genre)
        advise = $('<div/>').insertAfter($('.elgg-breadcrumbs'));
        advise.html('Salve amministratore, ti ricordo che stai modificando la pagina di un utente.').addClass('foowd-user-settings-admin');
        # agli amministratori non consento di modificare la password
        $('p input[name*="password"]').closest('p').remove();

    if($('[name="Genre"]').val() == 'evaluating')
        advise = $('<div/>').insertAfter($('.elgg-breadcrumbs'));
        advise.html('La tua richiesta &egrave; in fase di approvazione. <br/>Di seguito puoi visionare i dati che hai inserito:').addClass('foowd-user-settings-admin-evaluating'); 
    
        




    ################## PARTE ADIBITA ALLA VALIDAZIONE ########################


    form = require('foowdFormCheck')

    Jhook = $('#offer-hook')
    # Jform = Jhook.parents('form:first')
    Jgenre = $('[name="Genre"]')


    # for each input
    fct = form.factory();
    ar = []

    # $('<span/>',{'html':'**','class':'extra-Site'}).appendTo($('label[for="Site"]'))
    
    # uso un hook perche' devo essere sicuro di fare i controlli sull'utente owner, e non sul loggato (che potrebbe essere l'amministratore che modifica)
    _usernameBefore = $('[name="hookUsernameBefore"]').val().toLowerCase()
    _emailBefore = $('[name="hookEmailBefore"]').val().toLowerCase()
    
    ajaxCheck = ()->
        # se questi dati non vengono modificati, e' inutile fare il check
        v = @el.val().trim().toLowerCase()
        if @key is 'Username' and v is _usernameBefore
            @status = true
            return
        if @key is 'email' and v is _emailBefore
            @status = true
            return
        url=elgg.get_site_url()+'foowd_utility/user-check?'+ @key.toLowerCase() + '=' + v
        # console.log url
        # console.log v
        # console.log @key
        $.ajax({
            'url': url,
            'method' : 'GET',
            success: (resultText, success, xhr)=>
                # console.log(resultText)
                obj = JSON.parse(resultText)
                ret= false
                if typeof obj is 'object'
                    console.log obj
                    # true se la l'utente o la mail esistono
                    if obj[@key.toLowerCase()]
                        @error('Qesto valore e\' gia\' utilizzato. Prova con un altro')
                        ret = false
                    # secondo e' vero se sono validati da elgg
                    else if not obj['elgg_validate_' + @key.toLowerCase()]
                        @error('Qesto valore e\' in un formato non accettato. Prova con un altro')
                        ret = false
                    else
                        ret = true

                @status = ret
        });



    # NB: il campo "name" non e' univoco: utenti differenti possono avere lo stesso Name
    # ar.push({cls:'Text', obj:{inpt:'form.elgg-form-usersettings-save input[name="name"]', key:'name', el:'form.elgg-form-usersettings-save [name="name"]', msg: 'foowd:user:name:error'} })
    # ar.push({cls:'Email', obj:{inpt:'form.elgg-form-usersettings-save [name="Email"]', key:'Email', el:'form.elgg-form-usersettings-save [name="Email"]', msg: 'foowd:user:email:error', 'afterCheck': ajaxCheck} })
    
    # almeno di 4 lettere
    # ar.push({cls:'Text', obj:{inpt:'form.elgg-form-usersettings-save [name="username"]', key:'username', el:'form.elgg-form-usersettings-save [name="username"]', msg: 'foowd:user:username:error', 'afterCheck': ajaxCheck} })
    
    # ar.push({cls:'Text', obj:{inpt:'form.elgg-form-usersettings-save [name="password"]', key:'password', el:'form.elgg-form-usersettings-save [name="password"]', msg: 'foowd:user:password:error', 'afterCheck': ajaxCheck} })
    ar.push({cls:'Phone', obj:{inpt:'form.elgg-form-usersettings-save [name="Phone"]', key:'Phone', el:'form.elgg-form-usersettings-save [name="Phone"]', msg: 'foowd:user:phone:error'} })
    ar.push({cls:'WebDomain', obj:{inpt:'form.elgg-form-usersettings-save [name="Site"]', key:'Site', el:'form.elgg-form-usersettings-save [name="Site"]', msg: 'foowd:user:site:error'} })
    ar.push({cls:'Piva', obj:{inpt:'form.elgg-form-usersettings-save [name="Piva"]', key:'Piva', el:'form.elgg-form-usersettings-save [name="Piva"]', msg: 'foowd:user:piva:error'} })
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-usersettings-save [name="Address"]', key:'Address', el:'form.elgg-form-usersettings-save [name="Address"]', msg: 'foowd:user:address:error'} })
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-usersettings-save [name="Company"]', key:'Company', el:'form.elgg-form-usersettings-save [name="Company"]', msg: 'foowd:user:company:error'} })
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-usersettings-save [name="Owner"]', key:'Owner', el:'form.elgg-form-usersettings-save [name="Owner"]', msg: 'foowd:user:owner:error'} })
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-usersettings-save [name="Username"]', key:'Username', el:'form.elgg-form-usersettings-save [name="Username"]', msg: 'foowd:user:username:error', 'afterCheck': ajaxCheck} })
    ar.push({cls:'Email', obj:{inpt:'form.elgg-form-usersettings-save [name="email"]', key:'email', el:'form.elgg-form-usersettings-save [name="email"]', msg: 'foowd:user:email:error', 'afterCheck': ajaxCheck} })
    fct.pushFromArray(ar)


    needAr = ['email', 'Username']
    # username in minuscolo perche' intacco anche elgg!
    needArOfferente = ['Piva', 'Phone', 'Address', 'Company', 'Owner'] #location
    needArOfferente = needAr.concat needArOfferente
    # di default nessuno di questi e' obbligatorio
    noNeedAr = ['Site']
    setNeed = (bool)->

        localAr = if bool then needArOfferente else needAr
        
        fct.extraCheck = true
        for name in localAr
            if ($('[name="' + name + '"]').length <= 0 )
                console.log "manca il campo " + name
                fct.extraCheck = false
                break

        fct.each( ()->

            if (@key in localAr) 
                    @needle = true
            else if (@key in noNeedAr)
                    @needle = false
            else
                @needle = bool 
                
            return
            )

    setNeed(false)
    # campo extra per controllare che esistano certi elementi... non fa parte del prototipo della classe
    fct.extraCheck = true

    # trick per evitare che appaiano scritte di errore di elgg al momento del submit!
    elgg.ajax = false;
    # i campi che fanno chiamate ajax li triggero a parte: in questo modo le chiamate ajax non avvengono al submit
    # se non li ha mai cambiati, allora nel form non serve stare a controllarli: qui ho gia' dei dati validati, e mi preoccupo solo di quelli modificati
    $('input[name="email"], input[name="Username"]').on 'mouseout' , (e)->
        # il plugin delega sul document
        key = $(this).attr('name')
        ob = fct.getEl(key)
        ob.inpt.trigger('focusout')

    # ora il submit
    $('form.elgg-form-usersettings-save').submit (e)->

        check = true

        if not fct.extraCheck 
            alert('Errore nel form. Si consiglia di ricaricare la pagina')
            check = false

        if not check
            # evito che avvenga il submit
            e.preventDefault()
            # evito che si propagi ad eltri eventi
            e.stopPropagation()

    form.submit 'form.elgg-form-usersettings-save'


    if $('[name="js_admin"]').val() == 'amministratore' or Jgenre.val() != 'standard'
            # Jhook.fadeIn('slow')
            setNeed(true)
    else
        # Jhook.fadeOut('slow')
        Jhook.css({'display': 'none'})
        setNeed(false)
        $('#offer-hook').find('[type="text"]').each(
            ()->
                this.val('')
                $(this).attr('disabled',true)
        )
    
    
    checkGenre = ()->
        if this.val() is 'offerente'
        else
            # Jhook.fadeOut('slow')
            setNeed(false)
            # procedura di azzeramento del form extra

    # if Jgenre.lenght is 1 
    # rimuovo i check perche' ho modificato il form ed ora questi controlli avvengono via php
    # checkGenre.call(Jgenre)
    
    # Jgenre.on "change", ()->
    #     checkGenre.call($(this))


    # scambio l'ordine di visualizzazione di username e name
    # el1 = $('.elgg-form-usersettings-save').find('[name="username"]').parent();
    # el2 = $('.elgg-form-usersettings-save').find('[name="name"]').parent();

    # copy_to = el1.clone(true);
    # copy_from = el2.clone(true);
    # el2.replaceWith(copy_to);
    # el1.replaceWith(copy_from);

);
