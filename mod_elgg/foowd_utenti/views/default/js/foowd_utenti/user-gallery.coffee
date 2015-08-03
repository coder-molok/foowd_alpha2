
( (root, factory)-> 

    if typeof define is 'function' and define.amd
        # AMD. Register as an anonymous module.
        define(['elgg','page','jquery', 'foowd_utenti/gallery-crop-lightbox', 'foowd_utenti/file'], factory);
    else if typeof exports is 'object'
        module.exports = factory();
    else
        root.returnExports = factory();
  
)(this, 


()->

    # elgg = require('elgg')
    # page = require('page')
    $ = require('jquery')
    crop = require('foowd_utenti/gallery-crop-lightbox')

    # vedere come istanziato init di crop dentro a file
    file = require('foowd_utenti/file')  




    # anzitutto imposto la topbar in modo che risulti sotto al lightbox
    $('.elgg-page-topbar').css({"z-index":"1"});


    ####### parametri iniziali
    par =
        "urlF" : document.getElementById('url').href
        "guid" : $('.guid').attr('data-num')
        # "storageImg" : elgg.get_site_url() + page.foowdStorage

    extra =
        "formData" : {"guid": par.guid, "action":"saveFile"} 

    
    ######### creo l'inpu per il crop
    crop.create().initialize($.extend(file.fileCropInit(), extra) )


    ############################# CARICAMENTO E VISUALIZZAZIONE IMMAGINI
    # genero la stringa template
    # gli passo solamente l'elemento che contiene i dati da utilizzare
    template = (obj)->
        if not this.Id? then this.Id=0
        if not obj.id
            this.Id++
            obj.id = this.Id
        # console.log obj.id
        content = $('#imgTmpl').html()
        content = content.replace(/_imgSource/g, obj.src)
        content = content.replace(/_imgOriginal/g, obj.original)
        content = content.replace(/_imgHost/g, obj.host)
        content = content.replace(/_imgId/g, obj.id)
        return content

    # quando chiudo il box
    $( document ).on "foowd:lightbox:close", (e, mydata)->

        if mydata.action is 'add'
            send = $.extend({"src":mydata.targetFile}, mydata.crop)
            send.action = 'cropFile'
            send.useReturned = (ret)->
                # console.log(ret.src)
                content = template(ret)
                $('#gallery-container').append(content)

            mydata.Jbox.parent().parent().remove()
            # prima save e poi crop: l'ordine e' importante
            mydata.send = send
            makeCrop(mydata)
            crop.create().initialize($.extend(file.fileCropInit(), extra))

        if mydata.action is 'update'
            Jimg = $(mydata.imgSelector)
            send = mydata.crop
            send.action = 'updateFile'
            send.src = Jimg.attr('data-original')
            send.useReturned = (ret)->
                if(ret.response)
                    d = new Date();
                    src = Jimg.attr('src')
                    Jimg.attr("src", src+'?'+d.getTime());
                return
            # prima save e poi crop: l'ordine e' importante
            mydata.send = send
            # console.log send
            makeCrop(mydata)

        return

    # gli servono i parametri action per la chiamata e useReturned callback per manipolare i dati ritornati
    makeCrop = (obj)->

        callback = obj.send.useReturned
        delete obj.send.useReturned
        # console.log obj
        $.ajax({
          method: "GET",
          url: par.urlF,
          data: obj.send,
          success: (data)->
            # console.log(data)
            data = JSON.parse(data)
            if(data.response)
                ret=
                    src : obj.profileHost+obj.dirName+'/medium/'+obj.name;
                    original : obj.targetFile
                    host: obj.profileHost+obj.dirName+'/'+obj.name;
                    response: true
                # console.log(ret)
                callback(ret)
        })



    ############ Gestione Eventi 
    # vedi http://stackoverflow.com/questions/8110934/direct-vs-delegated-jquery-on
    
    ## comportamento sulle immagini
    $('#gallery-container').on 'mouseover', '.single' , ()->
        $(this).find('a').css("display":"block");

    $('#gallery-container').on 'mouseleave', '.single' , ()->
        $(this).find('a').css("display":"none");

    $('#gallery-container').on 'click', '.delete' , ()->
        original = $(this).siblings('img').attr('data-original')
        box = $(this).parent();
        dat = 
            'src': original
            action: 'removeDir'
            subdir: 'profile'
            guid: par.guid

        $.ajax({
          method: "GET",
          url: par.urlF,
          data: dat,
          success: (data)->
            # console.log JSON.stringify data
            data = JSON.parse(data)
            if(data.response) then box.remove()
        })
        

    $( '#gallery-container' ).on "click", '.single .change' , (e)->
        Jimg = $(this).siblings('img').first()
        selector = Jimg.attr('data-id');
        sourceImg = '[data-id="' + selector + '"]'
        $(document).trigger('foowd:load:img', {"imgSelector": sourceImg});
        return
    

    $(window).on 'load', ()->
        $('.hook').each ()->
            Jel = $(this)
            obj =
                src : Jel.attr('data-src')
                original : Jel.attr('data-original')
                host : Jel.attr('data-host')

            content = template(obj)
            $('#gallery-container').append(content)
            $(this).remove()



);