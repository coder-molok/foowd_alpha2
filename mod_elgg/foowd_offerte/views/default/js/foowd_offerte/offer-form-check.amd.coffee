
( (root, factory)-> 

    if typeof define is 'function' and define.amd
        # AMD. Register as an anonymous module.
        define([], factory);
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

            @el = $(@obj.el)
            @inpt = $(@obj.inpt)
            @key = @obj.key

            if @obj.trigger?
                $(document).on @obj.trigger , ->
                    first = false
                    if not that.check()  
                        that.error()
                    else 
                        that.clean()

            that = this
            first = true
            @inpt .on "focusout keydown click", ()->
                first = false
                if not that.check()  
                    that.error()
                else 
                    that.clean()

            @inpt .on "mouseout", ()->
                if !first 
                    if not that.check()  
                        that.error()
                    else 
                        that.clean()

        color: (color) ->
            @inpt.css(
                "background-color": color
                )

        check: ()->
            return

        error: ->
            #console.log "error-#{@el.attr 'name'}"
            @color "rgba(255, 0, 0, 0.17)"

            #se c'e' lo rimuovo
            $(".error-#{@el.attr 'name'}").remove()

            #console.log @msg
            $('<span/>',
                "class": "error-#{@el.attr 'name'}"
                "html": @msg
                ).
                #appendTo(@el.parent().find('label'))
                appendTo("label[for*=#{@key}]")
            console.log "appeso #{@key}"
            
        clean: ->
            $(".error-#{@el.attr 'name'}").remove()
            @color ''
 

    class Price extends Input
        check: ->
            #@key = @key
            v = @el.val().trim()
            if isFinite(v) and v isnt '' then true else false

    class Text extends Input
        check: ->
            v = @el.val().trim()
            if not v then false else true

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

    # se il div esiste ritorna true, altrimenti false
    class Div extends Input
        check: ->
            console.log @obj.el
            v = document.querySelectorAll(@obj.el)
            v = v.length
            console.log "#{v} fatto" 
            if v > 0 then true else false


    class InputFactory 

        input =
            'Name': ['Text', 'Il campo non puo\' essere vuoto']
            #'Description': ['Text', 'Il campo non puo\' essere vuoto']
            'Minqt-integer':['Price', 'Il campo e\' obbligatorio']
            'Maxqt-integer':['Larger', 'La quantita\' massima deve superare o eguagliare quella minima.<br/>    Se non vuoi inserire un massimo, cancella i numeri dal campo sottostante. ']
            'Price-integer':['Price',  'Il campo e\' obbligatorio']
            'Tag': ['Div', 'Devi selezionare almeno un tag', '.search-choice', 'foowd:update:tag']
            'file' : ['Div', 'Non hai aggiunto alcuna immagine', '#sorgente', 'foowd:update:file']

        constructor: ->
            @factory = []
            for key,cls of input 
                tmp = cls[0].toString()
                inpt = 'input[name*='+key+']'
                selector = '[name*='+key+']'
                if key is 'Tag' then inpt = '.chosen-choices'
                #else if key is 'file' then selector = '"#sorgente"'
                if cls[2]? then selector = cls[2]
                obj = 
                    "el" : selector
                    "key": key.split('-')[0]
                    "inpt": inpt

                if cls[3]? then obj.trigger = cls[3]

                tmp = eval("new "+tmp+'(' + JSON.stringify(obj) + ')' )
                tmp.msg = cls[1]
                @factory.push tmp
                #i.msg = cls[1]
                 #i

            #console.log @factory

            #for key in @factory
            #   console.log key.el
            

        checkAll: ->
            check = true
            for inpt in @factory
                if not inpt.check()
                    check = false
                    inpt.error()
            return check
            
    
    fac = new InputFactory()

    $('form').on 'submit', (e)->

        if not fac.checkAll() 
             e.preventDefault()
             alert 'Devi finire di compilare dei campi'

);