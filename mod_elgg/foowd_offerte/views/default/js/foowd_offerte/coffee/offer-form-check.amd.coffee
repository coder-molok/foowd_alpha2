
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
            #focusout mouseout
            @inpt .on "keyup", ( inptOn = ()->
                if !first
                    # aggiungo il controllo in differita di un secondo dall'utlima immissione
                    # per rendere meno stressante il controllo
                    clearTimeout(that.timeout);
                    that.timeout = setTimeout ()->
                        if not that.check()  
                            that.error()
                        else 
                            that.clean()

                    , 1000
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

            Min = $("[name*=Minqt]").val().trim()
            if Min is ''
                @msg = 'Devi prima inserire la quantit&agrave; minima'
                @el.val('')
                return false

            if Max is ''
                @msg = 'Devi prima inserire la quantit&agrave; Massima ( 0 se non vuoi impostare un massimo)'
                @el.val('')
                return false

            if not re.test(Max) 
                @msg = 'foowd:' +@key.toLowerCase()+':error' 
                return false

            # console.log "#{Min} e #{Max}"

            if parseFloat(Max) == 0
                return true

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
            # v = $('iframe[class*="cke_wys"]').contents().find('body').first().text().trim()
            v = $('[name="Description"]').val().trim()
            # alert(v)
            if v is '' 
                return false 
            else
                @clean()
                return true

    class Expiration extends Input
        

        check: ->
            # se e' vuolo lo lascio stare
            # console.log(@el.val())           
            if @el.val() == ''
                @clean()
                return true

            exp = __stringToDate(@el.val() ,'yyyy-mm-dd hh:ii:ss')
            now = new Date()
            # alert(printDate(exp))
            if(exp > now) 
                @clean()
                return true
            else
                return false

        # scrivo la data in stringa
        printDate = (m)->
            console.log(m)
            str = m.getUTCFullYear() + "/" + ( m.getUTCMonth() + 1 ) + "/" + m.getUTCDate() + " " + m.getUTCHours() + ":" + m.getUTCMinutes() + ":" + m.getUTCSeconds()
            return str

    # trasformo una stringa preformattata in una data
    #  usage: stringToDate('2015-10-28 09:59:00', 'yyyy-mm-dd hh:ii:ss')
    __stringToDate = (_date,_format)->
        # e' importante l'ordine, visto che poi la passo a 
        dateItems = ['yyyy', 'mm', 'dd', 'hh', 'ii', 'ss']
        dateApply = []

        for key in dateItems
            start = _format.indexOf key
            if start < 0 
                num = 0
            else
                lgth = key.length
                num = _date.substr(start, lgth)
                if key is 'mm' then num = num - 1

            dateApply.push parseInt(num)

        arg = dateApply.join(',')
        formatedDate = eval( 'new Date(' + arg + ')' );
        return formatedDate;


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
            # 'Minqt' : ['Minqt']
            # 'Maxqt' : ['Maxqt']
            'Quota' : ['Minqt']
            'Unit'  : ['Text']
            #'Tag': ['Div', 'Devi selezionare almeno un tag', '.search-choice', 'foowd:update:tag']
            'Tag': ['Div', '.search-choice', 'foowd:update:tag']# l'ultimo e' il trigger event impostato con chosen
            'file' : ['Div',  '#sorgente']#, 'foowd:update:file']
            'Expiration'  : ['Expiration', '[name="Expiration"]', 'foowd:update:expiration']
            
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
    
    # # Encode/decode htmlentities
    # krEncodeEntities = (s)->
    #     return $("<div/>").text(s).html();

    # # da codice la trasforma in visualizzazione
    # krDecodeEntities = (s)->
    #     return $("<div/>").html(s).text();

    # # controllo dinamico sul form input   
    # desc = $('[name="Description"]')  
    # # desc.css('background-color', 'red')
    # sanitizeInput = (Jel)->
    #     text = Jel.val()
    #     # rimuovo html
    #     text = text.replace(/<[^>]+>/g, '');
    #     Jel.val(text);

    # monitorInput = (Jel)->
    #     Jel.on 'paste', ()->
    #         # alert $(this).val()
    #         ((J)->
    #             setTimeout ()->
    #                 sanitizeInput(J) 
    #             , 100 
    #         )($(this))

    #     Jel.on 'keyup', ()->
    #         # alert $(this).val()
    #         sanitizeInput($(this))

    # monitorInput(desc)
    

    # prepareInput = (Jel)->
    #     text = Jel.val()
    #     rx = /\n/g ;
    #     # text = text.replace(rx, '')
    #     html = krDecodeEntities(text)
    #     # alert html
    #     # se voglio salvare in formato codificato
    #     Jel.val(html+'stringa di test')





    
    fac = new InputFactory()

    $('form').unbind();

    $('form').on 'submit', (e)->
        if $('.foowd-advise-pending').length > 0
            e.preventDefault()
            elgg.register_error('Il form e\' bloccato.<br/> Vedi intestazione per dettagli.')
            # definito in foowd-main.coffee: dopo qualche secondo rimuovo il popup
            loom.removeSystemErrorPopup();

        if prepareInput?
            prepareInput(desc)

        # e.preventDefault()
        if not fac.checkAll() 
             e.preventDefault()
             alert 'Devi finire di compilare dei campi'
             # tolgo il focus dal bottone del form
             $(this).find('[type="submit"]').blur()
             

);
