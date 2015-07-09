
( (root, factory)-> 

    if typeof define is 'function' and define.amd
        # AMD. Register as an anonymous module.
        define(['jquery','elgg'], factory($,elgg));

    # else if typeof exports is 'object'
    #     # Node. Does not work with strict CommonJS, but
    #     # only CommonJS-like environments that support module.exports,
    #     # like Node.
    #     module.exports = factory(require['jquery'], require['elgg']);
    # else
    #     # Browser globals (root is window)
    #     root.FoowdFormCheck = factory(root.jQuery, root.elgg);
  
)(this, 


($,elgg)->

    loom = this

    # $ = require('jquery')
    # elgg = require('elgg')

    # con @ impongo il this!
    class Input 
        # nel caso lo volessi usare come variabile privata
        # dovrei dichiararlo qui
        # el = null
        # 
        # ATTENZIONE!! vedi
        # http://bestmike007.com/blog/2014/11/06/a-note-about-private-class-members-in-coffeescript-slash-javascript/

        #ho bisogno di 
        #   inpt,       selelettore che rappresenta campo input che uso per gli eventi jquery focusout, mouseout, keyup. 
        #   key,        la chiave per identificare il label for. Viene utilizzato anche per scrivere il messaggio di errore, usando anche il file di language del plugin
        #   el,         selettore dell'elemento jquery su cui fare il check
        #   trigger,    evento DOCUMENT al cui verificarsi avvengono checK() e successivamente error() o clean()
        #   needle,     di default e' true. Se true il campo e' obbligatorio (quindi deve essere per forza non vuoto); se false invece deve essere diverso da un campo vuoto, e naturalmente matchare i risultati
        #   afterCheck, callback che usa il "this" e viene eseguita quando il check ritorna true. Se si vuole utilizzarla per la validazione al submit, allora puo' modificare il parametro @status
        constructor: (@obj) ->
            # console.log(@Jselector)
            
            #default
            @needle = true

            @el = $(@obj.el)
            @inpt = $(@obj.inpt)
            @key = @obj.key
            @msg = @obj.msg

            
            if typeof @obj.needle is 'boolean' then @needle = @obj.needle
            if typeof @obj.afterCheck is 'function' then @afterCheck = @obj.afterCheck

            # NB:   al posto di utilizzare il that, 
            #       con coffeescript e' possibile utilizzare la fat arrow =>
            that = this
            first = true

            # se all'oggetto e' attribuito un evento di trigger
            # dalla prima volta che entra nel campo input,
            # considero che debba rispettare i vincoli
            
            @inpt .on "click focus", ->
                first = false
                return
            
            # trigger extra per gli oggetti associati a un evento di altri plugin
            # (solo caricamento immagine e inserimento tag)
            if @obj.trigger?
                $(document).on @obj.trigger , ->
                    first = false
                    that.action()
            

            #vincoli da rispettare
            @inpt .on "focusout mouseout keyup", ()->
                if !first
                    that.action()
                    
            
        color: (color) ->
            @inpt.css(
                "background-color": color
                )

        action: ->

            if not @check()  
                @error()
    
            if @allCheck() 
                @clean()
    
            return

        allCheck: ->
            if not @need()
                # console.log 'inside allcheck need'
                status = true
            else 
                # console.log 'inside allcheck else'
                if @check() and typeof @afterCheck is "function"
                    @afterCheck.call(this) 
                    # nel caso venga impostato nella afterCheck
                    if @status? then status = @status
                else
                    status = @check() 

            return status

        check: ()->
            return

        ## se non e' impostato va bene, ma se lo e', in ogni caso devo fare dei controlli
        # se ritorna true, vuol dire che il campo e' obbligatorio e quindi continuo col check
        # se false allora posso evitare di fare il check
        need: ()->
            if(@needle) 
                return true
            else
                v = @el.val().trim()
                if v isnt '' then true else false 

        error: (msg)->
            if not msg? then msg = @msg
            # console.log "error-#{@el.attr 'name'}"
            #@color "rgba(255, 0, 0, 0.17)"

            #se c'e' lo rimuovo
            $(".error-#{@key}").remove()
            
            # $(".error-#{@key}").each( ()->
            #     $(this).css({'background-color':'violet'})
            #     alert($(this).html() + msg)
            #     )

            #console.log @msg
            $('<span/>',
                "class": "error-#{@key}"#"error-#{@el.attr 'name'}"
                "html": elgg.echo msg
                ).
                #appendTo(@el.parent().find('label'))
                appendTo("label[for*=#{@key}]")
            # console.log "appeso #{@key}"
            
        clean: ->
            #$(".error-#{@el.attr 'name'}").remove()
            $(".error-#{@key}").remove()
            #@color ''
 

    class Price extends Input
        check: ->
            #@key = @key
            re = new RegExp(/^\d{1,8}(\.\d{0,2})?$/)
            v = @el.val().trim()
            if re.test(v) and v isnt '' then true else false

    class Minqt extends Input
        check: ->
            #@key = @key
            re = new RegExp(/^\d{1,5}(\.\d{0,3})?$/)
            v = @el.val().trim()
            if re.test(v) and v isnt '' and parseFloat(v)>0.0 then true else false

    class Maxqt extends Input
        check: ->
            #@key = @key
            re = new RegExp(/^\d{1,5}(\.\d{0,3})?$/)
            Max = @el.val().trim()

            if Max is '' then return true

            Min = $("[name*=Minqt]").val().trim()
            if Min is ''
                @msg = 'Devi prima inserire la quantit&agrave; massima'
                @el.val('')
                return false

            if not re.test(Max) 
                @msg = 'foowd:' +@key.toLowerCase()+':error' 
                return false

            # console.log "#{Min} e #{Max}"
            if parseFloat(Min) > parseFloat(Max)
                @msg = 'foowd:' +@key.toLowerCase()+':error:larger' 
                return false

            return true


    class Text extends Input
        check: ->
            v = @el.val().trim()  
            if not v then false else true

    class Phone extends Input
        check: ->
            re = new RegExp(/^\d{9,11}$/)
            v = @el.val().trim()  
            if re.test(v) then true else false

    class WebDomain extends Input
        check: ->
            re = new RegExp(/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/)
            v = @el.val().trim()  
            if re.test(v) then true else false

    # partita iva
    class Piva extends Input
        check: ->
            re = new RegExp(/^[0-9]{11}$/)
            v = @el.val().trim()  
            if re.test(v) then true else false

    class Email extends Input
        check: ->
            re = new RegExp(/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i)
            v = @el.val().trim()  
            if re.test(v) then true else false

          

    ###
    class Larger extends Input   
        maxInt = $("[name*=Maxqt-integer]")
        maxDec = $("[name*=Maxqt-decimal]")
        minInt = $("[name*=Minqt-integer]")
        minDec = $("[name*=Minqt-decimal]")


        check: ->
            v = @el.val().trim()
            # se e' vuoto, allora non ci sono problemi!
            if not isFinite(v) or v is ''
                true
            else
                Max = maxInt.val() + '.' +maxDec.val()
                Min = minInt.val() + '.' +minDec.val()
                #alert "#{Min} e #{Max}"
                if Max < Min then false else true
    ###

    # se il div esiste ritorna true, altrimenti false
    class Div extends Input
        check: ->
            #console.log @obj.el
            v = document.querySelectorAll(@obj.el)
            v = v.length
            #console.log "#{v} fatto" 
            if v > 0 then true else false


    class InputFactory 
       

        # object of object: used sostantially as array
        constructor: ->
            @factory = {}

        # array: classname, object to create class
        pushFromArray: (ar)->
            for row in ar
                tmp = row.cls
                # tmp = eval("new "+tmp+'(' + JSON.stringify(row.obj) + ')' )
                tmp = eval( tmp )
                @factory[row.obj.key] = new tmp(row.obj)
                # ` this.factory[row.obj.key]=tmp `
                # @factory.push(tmp)
                # console.log JSON.stringify @factory
           

        checkAll: ->
            check = true
            for key,inpt of @factory
                if not inpt.allCheck()
                    check = false
                    # console.log key
                    inpt.action()
            return check

        # el e' la key assegnata all'elemento, che funge da indice dell'occetto factory
        getEl: (el)->
            return @factory[el]

        # funzione da eseguire su ogni membro dell'oggetto factory (corrispondenza key e nome membro)
        each: (callback)->
            if typeof callback isnt "function" then return false
            for key,inpt of @factory
                callback.call(inpt)
            return @factory            

            
    $("head").append("<style id='foowd-form-check-css'></style>");

    mystyle =   '
                    [class^="error-"] {
                        color: red;
                        padding: 5px;
                        font-style:italic;
                    }
                '
    $("#foowd-form-check-css").text(mystyle);
    


    ret = {}

    ret.factory = ->
        this.fac = new InputFactory()

    # ret.init = ->
    #     console.log 'init works!'

    # el is surely the slector of the form which is submitted
    # callbackPre, function to execute before
    ret.submit = (el, callbackPre)->
        $(el).on 'submit', (e)=>
            proceed = true
            #callbackPre retur true for continue the check
            if typeof callbackPre is "function" and not callbackPre() 
                e.preventDefault();
                return

            if not this.fac.checkAll() 
                 e.preventDefault()
                 alert 'Devi finire di compilare dei campi'


    return ret

);