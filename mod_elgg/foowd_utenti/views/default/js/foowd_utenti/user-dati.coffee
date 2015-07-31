
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
    Jform = Jhook.parents('form:first')
    Jgenre = $('[name=Genre]')
    

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
        console.log v
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
    # ar.push({cls:'Text', obj:{inpt:'form.elgg-form-foowd-dati input[name="name"]', key:'name', el:'form.elgg-form-foowd-dati [name="name"]', msg: 'foowd:user:name:error'} })
    ar.push({cls:'Email', obj:{inpt:'form.elgg-form-foowd-dati [name="email"]', key:'email', el:'form.elgg-form-foowd-dati [name="email"]', msg: 'foowd:user:email:error', 'afterCheck': ajaxCheck} })
    
    # almeno di 4 lettere
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-foowd-dati [name="username"]', key:'username', el:'form.elgg-form-foowd-dati [name="username"]', msg: 'foowd:user:username:error', 'afterCheck': ajaxCheck} })
    
    # ar.push({cls:'Text', obj:{inpt:'form.elgg-form-foowd-dati [name="password"]', key:'password', el:'form.elgg-form-foowd-dati [name="password"]', msg: 'foowd:user:password:error', 'afterCheck': ajaxCheck} })
    ar.push({cls:'Phone', obj:{inpt:'form.elgg-form-foowd-dati [name="Phone"]', key:'Phone', el:'form.elgg-form-foowd-dati [name="Phone"]', msg: 'foowd:user:phone:error'} })
    ar.push({cls:'WebDomain', obj:{inpt:'form.elgg-form-foowd-dati [name="Site"]', key:'Site', el:'form.elgg-form-foowd-dati [name="Site"]', msg: 'foowd:user:site:error'} })
    ar.push({cls:'Piva', obj:{inpt:'form.elgg-form-foowd-dati [name="Piva"]', key:'Piva', el:'form.elgg-form-foowd-dati [name="Piva"]', msg: 'foowd:user:piva:error'} })
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-foowd-dati [name="Address"]', key:'Address', el:'form.elgg-form-foowd-dati [name="Address"]', msg: 'foowd:user:address:error'} })
    ar.push({cls:'Text', obj:{inpt:'form.elgg-form-foowd-dati [name="Company"]', key:'Company', el:'form.elgg-form-foowd-dati [name="Company"]', msg: 'foowd:user:company:error'} })
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


    # nascondo il campo "name" in quanto forviante
    $('.mtm').css({'display':'none'})
    
    form.submit 'form.elgg-form-foowd-dati',
        ()->
            alert('sub')

            # impongo che nome visualizzato sia il nick name
            Jname = $('form.elgg-form-foowd-dati [name="name"]').val($('form.elgg-form-foowd-dati [name="username"]').val())


            pwd = $('form.elgg-form-foowd-dati [name="password"]').val()
            pwd2 = $('form.elgg-form-foowd-dati [name="password2"]').val()
            
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
