
( (root, factory)-> 
    if typeof define is 'function' and define.amd
        # AMD. Register as an anonymous module.
        define(['elgg','jquery'], factory);
    else if typeof exports is 'object'
        # Node. Does not work with strict CommonJS, but
        # only CommonJS-like environments that support module.exports,
        # like Node.
        module.exports = factory();
    else
        # Browser globals (root is window)
        root.returnExports = factory();
  
)(this, 


()->

    loom = this

    $ = require('jquery')
    elgg = require('elgg')

    # con @ impongo il this!
    class Input 
        # nel caso lo volessi usare come variabile privata
        # dovrei dichiararlo qui
        # el = null
        # 
        # ATTENZIONE!! vedi
        # http://bestmike007.com/blog/2014/11/06/a-note-about-private-class-members-in-coffeescript-slash-javascript/

        #ho bisogno di 
        #   inpt, il campo input che uso per gli eventi jquery
        #   key, la chiave per identificare il label for
        #   el, l'elemento jquery su cui fare il check
        constructor: (@obj) ->
            # console.log(@Jselector)

            # posso passare o query string o elementi jqueryi direttamente
            if typeof @obj.el is 'object'
                @el = @obj.el
                console.log('oggetto')
                console.log @obj
            else
                @el = $(@obj.el)

            @inpt = $(@obj.inpt)
            @key = @obj.key

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
                    if not that.check()  
                        that.error()
                    else 
                        that.clean()
                
            

            #vincoli da rispettare
            @inpt .on "focusout mouseout keyup", ( inptOn = ()->
                if !first
                    if not that.check()  
                        that.error()
                    else 
                        that.clean()
            )
            
            ###
            @inpt .on "mouseout", ()->
                if !first 
                    if not that.check()  
                        that.error()
                    else 
                        that.clean()
            ###
        color: (color) ->
            @inpt.css(
                "background-color": color
                )

        check: ()->
            return

        error: ->
            #console.log "error-#{@el.attr 'name'}"
            #@color "rgba(255, 0, 0, 0.17)"

            #se c'e' lo rimuovo
            #$(".error-#{@el.attr 'name'}").remove()
            $(".error-#{@key}").remove()

            #console.log @msg
            $('<span/>',
                "class": "error-#{@key}"#"error-#{@el.attr 'name'}"
                "html": elgg.echo @msg
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
                @msg = 'Devi prima inserire la quantit&agrave; minima'
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

    class IframeText extends Input
        check: ->
            v = $('iframe[class*="cke_wys"]').contents().find('body').first().text().trim()
            # alert(v)
            if v is '' 
                return false 
            else
                @clean()
                return true


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

        input =
            'Name': ['Text']
            'Description': ['IframeText']
            #'Minqt-integer':['Price', 'Il campo e\' obbligatorio']
            #'Maxqt-integer':['Larger', 'La quantita\' massima deve superare o eguagliare quella minima.<br/>    Se non vuoi inserire un massimo, cancella i numeri dal campo sottostante. ']
            #'Price-integer':['Price',  'Il campo e\' obbligatorio']
            'Price' : ['Price']
            'Minqt' : ['Minqt']
            'Maxqt' : ['Maxqt']
            'Quota' : ['Minqt']
            'Unit'  : ['Text']
            #'Tag': ['Div', 'Devi selezionare almeno un tag', '.search-choice', 'foowd:update:tag']
            'Tag': ['Div', '.search-choice', 'foowd:update:tag']# l'ultimo e' il trigger event impostato con chosen
            'file' : ['Div',  '#sorgente']#, 'foowd:update:file']
            
        constructor: ->
            @factory = []
            for key,cls of input 
                tmp = cls[0].toString()
                inpt = 'input[name*='+key+']'
                selector = '[name*='+key+']'
                if key is 'Description' then selector = 'iframe[class*="cke_wys"]'
                if key is 'Tag' then inpt = '.chosen-choices'
                if key is 'Unit' then inpt = 'select[name=Unit]'
                #else if key is 'file' then selector = '"#sorgente"'
                if cls[1]? then selector = cls[1]
                obj = 
                    "el" : selector
                    "key": key.split('-')[0]
                    "inpt": inpt

                if cls[2]? then obj.trigger = cls[2]

                tmp = eval("new "+tmp+'(' + JSON.stringify(obj) + ')' )
                tmp.msg = 'foowd:'+ key.toLowerCase() + ':error'
                @factory.push tmp
                #i.msg = cls[1]
                 #i

            #console.log @factory

            #for key in @factory
            #   console.log key.el
        

        # gestisco la preview della quota
        quotaDivs = '[name="Quota"], select[name="Unit"], [name="UnitExtra"]'
        JquotaPrev = $('#quota-preview')
        $(quotaDivs).on "change keyup", ()->
            str = $('[name="Quota"]').val() + ' ' + $('select[name="Unit"]').val() + ' ' + $('[name="UnitExtra"]').val()
            JquotaPrev.html(str)
        # forzo un trigger per far eseguire una volta di default
        $(quotaDivs).trigger('change')

        # Jframe = $('').first()
        # console.log Jframe
        # $(window).on 'load', ()->
        #     Jframe = $("iframe").contents()
        #     Jframe.on 'click', (e)->
        #         console.log Jframe.selector
        #         console.log $(this).val()
            

        checkAll: ->
            check = true
            for inpt in @factory
                if not inpt.check()
                    check = false
                    inpt.error()

            # controllo esplicito del campo description
            # descTxt = $('iframe[class*="cke_wys"]').contents().find('body').text() 
            # if descTxt is ''
            #     alert('txt empty')
            #     check = false

            return check
            
    
    fac = new InputFactory()

    $('form').unbind();

    $('form').on 'submit', (e)->
        # e.preventDefault()
        if not fac.checkAll() 
             e.preventDefault()
             alert 'Devi finire di compilare dei campi'
             # tolgo il focus dal bottone del form
             $(this).find('[type="submit"]').blur()
             

);