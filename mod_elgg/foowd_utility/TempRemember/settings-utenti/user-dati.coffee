
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
    form = require('foowdFormCheck')

    Jhook = $('#offer-hook')
    # Jform = Jhook.parents('form:first')
    Jgenre = $('[name="Genre"]')

    # for each input
    fct = form.factory();
    ar = []

    # preimpostati
    # fielsd
    flds = ['Email', 'username', 'name' , 'password']
    for va in flds
        JmailLabel = $('[name="'+va+'"]').prevUntil('','label');
        JmailLabel.attr({'for': va})

    # $('<span/>',{'html':'**','class':'extra-Site'}).appendTo($('label[for="Site"]'))
    


    ajaxCheck = ()->

        v = @el.val().trim()
        url=elgg.get_site_url()+'foowd_utility/user-check?foowd-dati=true&guid='+elgg.get_logged_in_user_guid()+'&'+@key+'='+v
        console.log v
        elgg.get(url, {
            success: (resultText, success, xhr)=>
                console.log(resultText)
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
    fct.pushFromArray(ar)
    # di default nessuno di questi e' obbligatorio

    # needAr = ['email', 'username','name']
    needAr = ['Piva', 'Phone', 'Location', 'Address', 'company']
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



);
