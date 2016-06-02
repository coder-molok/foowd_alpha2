
( (root, factory)-> 

    if typeof define is 'function' and define.amd
        # AMD. Register as an anonymous module.
        define(['jquery'], factory);
    else if typeof exports is 'object'
        module.exports = factory();
    else
        root.returnExports = factory();
  
)(this, 


()->

    #elgg = require('elgg')
    $ = require('jquery')

    # campi file

    fileField = (num)->
        Jrefr = $('label[for="Description"]').parent();
        if !Jrefr.length then Jrefr = $('.file-box-hook')
        if !Jrefr.length then alert 'errore'
        content = $('#fileTmpl').html()
        Jrefr.append(content.replace(/-num_par/g, num));
        return

    addFile = ()->
        ar = []
        check = []
        $('#offer-hook input[type="file"]').each ->
            v = $(this).val()
            n = $(this).attr('data-num');
            if v is '' 
                $('#file'+n+'-hook').remove()

                return true
            if n is '-num_par' then return true
            ar.push(n)

        m = Math.max.apply(Math,ar);
        if !isFinite(m) then m = 0
        m++
        fileField(m)
        return m


    # fileListener = ->
    #     $('#offer-hook input[type="file"]').on 'click', ->
    #         alert('clcik')


    fileCropInit = ->
        # fileListener()
        n = addFile()
        return initCrop(n)

    initCrop = (n)->
        return {
                # ricavo l'url
                urlF : document.getElementById('url').href
                # l'id del campo input che immagazzina i file, ovvero le immagini
                fileInput : '[name="file'+n+'"]'
                css: [
                    'mod/foowd_utility/js/imgareaselect/css/imgareaselect-default.css',
                    'mod/foowd_utility/js/foowd-crop/foowd-crop.css'
                ]
                # deve esistere, e li dentro verra' immagazzinata l'immagine, se gia' non esiste
                loadedImgContainer : '#file'+n+'-container'

                
                # l'id del tag img che funge da sorgente. Se esiste lo carica in $img privata del plugin, altrimenti lo creera' col caricamento dell'immagine
                sourceImg: '#file'+n+'-sorgente'
                # id del box che contiene tutte le immagini: appeso dopo $init.fileInput
                imgContainer:'#file'+n+'-image-container',
                imgAreaPrefix: 'file'+n
        }



    atLeastOne = ->
        check = false
        $('input[type="file"]').each ->
            v = $(this).val()
            n = $(this).attr('name');
            if v isnt '' then check = true
            # console.log( v + ' ' + n)
        return check



    # inizializzo il primo:
    # fileField(1)

    return{
        atLeastOne: atLeastOne,
        fileCropInit: fileCropInit
    }
        

);

###
<!-- <div id="fileTmpl" style="display:">
<div id="file_par-hook">
    <div>
        <label for="file_par">Carica l'immagine *</label><div style="color:red"></div><br>
        <input name="file_par" value="" class="elgg-input-file" type="file">
    </div>
    <center>
        <div id="file_par-container"></div>
    </center>
    <div class="crop">
            <input name="crop_file_par[x1]" value="" type="hidden">
            <input name="crop_file_par[y1]" value="" type="hidden">
            <input name="crop_file_par[x2]" value="" type="hidden">
            <input name="crop_file_par[y2]" value="" type="hidden">    
    </div>
</div>
</div> -->
###
