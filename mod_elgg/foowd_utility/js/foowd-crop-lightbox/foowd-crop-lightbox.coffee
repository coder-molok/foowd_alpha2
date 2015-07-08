( (root, factory)-> 

    if typeof define is 'function' and define.amd
        # AMD. Register as an anonymous module.
        define(['elgg','jquery'], factory);
    else if typeof exports is 'object'
        module.exports = factory();
    else
        root.returnExports = factory();
  
)(this, 


()->

    ###### NB: rivedere le differenze tra variabili statiche!!!
    # $ini dichiarato fuori da Gobj e' statica, pertanto non mi va bene!
    # le funzioni come setStyle() vanno bene anche fuori, perche' tanto sono le stesse e con gli stessi
    # parametri di tutte le istanze,
    # 
    # ma tutti gli $init invece devono appartenere all'oggetto specifico, pertanto li devo rendere dei metodi di Gobj!!!

    ## oggetto globale, ovvero quello che verra' ritornato dal plugin
    Gobj = ()->
        # nel caso lo voglia utilizzare come una funzione
        # if (!(this instanceof Gobj))
                # return new Gobj()
        console.log "Prototype: #{@prototype}"
        console.log "Constructor: #{@Constructor}"
        return

    elgg = require('elgg')
    $ = require('jquery')

    ############################################################################################
    ##### prima parte:  reperimento di url, spedizione a quest'ultimo e ritorno dell'immagine 
    #                   che viene inserita in un div
    

    Gobj.prototype.setInit = (obj)->
        this.nocss = false

        needle = ['urlF','loadedImgContainer','sourceImg','fileInput', 'imgContainer', 'css']
        for prop,val of obj 
            # $init[prop]=val
            this[prop] = val
            index = needle.indexOf(prop);
            if index > -1 then needle.splice(index, 1);

        for val in needle
            console.log "#{val} not setted: plugin could bump into errors"

        this.JloadedImgContainer = $(this.loadedImgContainer)
        this.JfileInput = $(this.fileInput)
        this.JsourceImg = $(this.sourceImg)
        this.JimgContainer = $(this.sourceImg)
        return

    # carico il foglio, o i fogli, di stile:
    Gobj.prototype.setStyle = ()->
        if !this.nocss
            cssToLoad = [].concat( this.css )
            for val in cssToLoad
                if (!$('link[href="' + val + '"]').length) then $('<link href="' + val + '" rel="stylesheet">').appendTo("head");


    #se l'immagine e' gia' esistente, allora provvedo subito ad inizializzare la funzione
    Gobj.prototype.initialize = (obj)->
        # inizializzo i parametri
        @setInit(obj)
        @setStyle()       

        that = this

        $(window).on 'load', ()=>
            # if $init.JloadedImgContainer.length isnt 1 then console.log('loadedImgContainer: il selettore non e\' univoco')

            # imposto la larghezza a 400
            w = 400
            # $init.JloadedImgContainer.width(w)
            # @Jimg.id = $sourceId
            # $img = $init.JloadedImgContainer
             
            if @JsourceImg.length isnt 1 
                # console.log('sourceImg: il selettore non e\' univoco')
                return

            alert('rivedere questa parte')
            # @Jimg = $init.JsourceImg
            # @Jimg.height() *= w/@Jimg.width()
            # @Jimg.width() = w
            # @start();
            #else
                # alert('nada');
            return   

        
        # quando carico un'immagine
        @JfileInput.on 'change', (e)->
            # controllo sui formati
            if(! this.value.match(/\.(jpg|jpeg|png|gif)$/i) )
                 alert('Sono validi solo formati jpg - jpeg - png - gif');
                 this.value = '';
                 return;
        
            # carico il file
            file = this.files[0];
        
            # preparo i dati da inviare
            formData = new FormData();
            formData.append(this.name, file);
            # console.log(JSON.stringify(formData));
        
            #guid = document.querySelector('input[name=guid]');
            # console.log(guid.value);
            #formData.append(guid.name)
            #, guid.value);
        
            # classe per visualizzare una piccola progressbar
            pop = new LoadPop();
            
            # alert(JSON.stringify(formData));
            # return;
            # proseguo con l'xmlhttprequest
            xhr = new XMLHttpRequest();
        
            xhr.addEventListener('progress', (e) ->
                done = e.position || e.loaded
                total = e.totalSize || e.total;
                percent = Math.floor(done/total*1000)#10;
                if(!isFinite(percent)) then percent = 100;
                pop.progress(percent);
                # console.log('xhr progress: ' + (Math.floor(done#total*1000)#10) + '%');
            , false);
            if ( xhr.upload )
                xhr.upload.onprogress = (e)->
                    done = e.position || e.loaded
                    total = e.totalSize || e.total;
                    percent = Math.floor(done/total*1000)#10;
                    if(!isFinite(percent)) then percent = 100;
                    pop.progress(percent);
                    # console.log('xhr.upload progress: ' + done + ' # ' + total + ' = ' + percent + '%');
            
            xhr.onreadystatechange = (e)->         
                if ( 4 == this.readyState ) 
                    pop.complete();
                    console.log(['xhr upload complete', e]);
                    # console.log(JSON.stringify(xhr.responseText));
    
                    try
                        obj = JSON.parse(this.responseText)
                    catch e
                       alert('invalid json')
        
                    if not obj? then return

                    that.Jimg = $('<img/>').attr('src', obj.preSrc+obj.src).load(
                        ()->
                            w = 400;
                            this.height *= w/this.width
                            this.width = w

                            $(this).css({'width': this.width, 'height': this.height})
                            that.JimgContainer.css({'display' : ''})
                            div = that.JloadedImgContainer;
                            # lo svuoto nel caso vi siano altre immagini
                            div.html('');
                            $(this).appendTo(div)
                            $( document ).trigger( "foowd:update:file" )                      
                            that.start()

                            return
                        )                    
    
        
            # $('body').append(JSON.stringify(xhr.responseText));
            # alert(xhr.responseText);
    
            xhr.open('post', that.urlF, true)
            # xhr.setRequestHeader("Content-Type","multipart#form-data");
            xhr.send(formData)
            return
        return 


    #immagine concreta
    # $img = new Image()
    #$img.src = document.getElementById('sorgente').getAttribute("src");

    #finestre oggetti preview associati alla finestre
    # $preWindos = [];

    #css
    # $margin = '20px';

    #oggeto imgAreaSelect: istanza
    #gli assegno anche le vecchie iabili
    # $ias = {};

    #oggetto globale che utilizzo solo in una funzione
    scale = 
        # nel caso lo voglia utilizzare come una funzione
        # if not (this instanceof Scale)
        #     obj = new Scale()
        #     return obj

        setScale : (num)->
            this.w = Math.round(num*scale.x);
            this.h = Math.round(num*scale.y);
            this.k = Math.min(scale.x, scale.y);
            this.l = Math.min(scale.w, scale.h);

            #post opzione quadrato: la larghezza e' fissa e si adatta l'altezza
            this.r = this.h/this.w;
            this.w = this.l;
            this.h = this.l / this.r;

        setL : (l1,l2)->
            this.l = Math.min(l1, l2);

    # funzione/classe globale
    LoadPop =  ()->
        # nel caso lo voglia utilizzare come una funzione
        if (!(this instanceof LoadPop))
                return new LoadPop()

        this.div=document.createElement("div");
        this.div.className = 'foowd-lightbox';
        document.body.appendChild(this.div);

        # container progress
        this.box = document.createElement('div');
        this.box.className='progress-container';
        this.div.appendChild(this.box);

        # la barra progress 
        this.x = document.createElement("PROGRESS");
        this.x.className = 'progress-bar';
        this.x.max = 100;
        this.x.value = 0;
        this.box.appendChild(this.x);

        # la scritta 
        this.t = document.createElement("span");
        this.t.className = 'progress-value';
        this.t.innerHTML = '0 %';
        this.box.appendChild(this.t);
        

        # infine sistemo il box centrandolo
        this.box.style.left = ($wSize.w - this.box.offsetWidth)/2 +'px';
        this.box.style.top = ($wSize.h - this.box.offsetHeight)/2 +'px';
        # this.x.insertAdjacentHTML( 'beforeBegin', '<br/>' );

        

        this.progress = (percent)->
            this.x.value = Math.floor(percent)
            this.t.innerHTML = Math.floor(percent)+' %'
            return 

        this.complete = ()->
            # console.log('done')
            this.div.remove();
            return

        return

    # dimensioni finestra
    # funzione di utiliti che va bene per tutte le istanze
    $wSize = (()->
        w = window
        d = document
        e = d.documentElement
        g = d.getElementsByTagName('body')[0]
        x = w.innerWidth || e.clientWidth || g.clientWidth
        y = w.innerHeight|| e.clientHeight|| g.clientHeight
        return{
            'w' : x,
            'h' : y
        }
    )()


    #############################################################
    ######## Ora inizia la parte dinamica di animazione #########
    
    ###################################################################
    ## inizilizzazione:                                              ##
    ## imposto le finestre e la funzione imgAreaSelect, con callback ##
    ###################################################################
    Gobj.prototype.start = ()->
        ## setto la scala: il valore piu piccolo corrisponde a 1 e l'altro scala in proporzione
        ## uso il piu piccolo in quanto sto usando l'overflow
        decimals = 100000; ## non penso di avere immagini che superino scale dei 1000px

        if @Jimg.width() >= @Jimg.height()
            scale.x = Math.round(decimals * @Jimg.width()/@Jimg.height() )/decimals;
            scale.y = 1;
        else
            scale.x = 1;
            scale.y = Math.round(decimals * @Jimg.height()/@Jimg.width() )/decimals;
        
    
        ## imposto la finestra di riferimento e prendo la sua sorgente
        div = @Jimg
        if !div.length then alert('div not exists');
        
        src = div.attr('src');
        if !src then alert('src not exists');
    
    
        ## se gia' presenti, elimino le altre finestre di preview
        i = $preWindos.length;
        `while(i--){
            $preWindos[i].remove();
            $preWindos.splice(i);
        }`
        
    
        ## costruisco le finestre di preview
        scale.setScale(100);
        $preWindos.push(new PrevWindow('small', div , scale ));
    
        scale.setScale(250);
        $preWindos.push(new PrevWindow('medium', div , scale ));
    
    
        ## resetto i dati nel caso avessi gia' caricato un'immagine rimuovendo le classi generate dal plugin
        x = document.querySelectorAll('[class^=imgareaselect]');
        `for ( i = 0; i < x.length; i++) {
            x[i].remove();
        }`
    
        ## imposto i dati per l'inizializzazione dello script di "crop"
        scale.setL(div.width, div.height);
    
        ## recupero eventuali valori di crop iniziali
        ar = ['x1', 'x2', 'y1', 'y2'];
        oldCrop = {};
        for variable,i in ar
            tmp = ar[i];
            val = $('input[name="crop_'+$init.JfileInput.attr('name')+'['+tmp+']"]').val();
            $Jcrop[tmp] = $('input[name="crop_'+$init.JfileInput.attr('name')+'['+tmp+']"]')
            console.log $('input[name="crop_'+$init.JfileInput.attr('name')+'['+tmp+']"]').length
            if val is ''
                ## faccio in modo che la finestra sia centrata nell'immagine
                switch tmp
                    when 'x1' then oldCrop.x1 = Math.round( (@Jimg.width() - scale.l) / 2 )
                    when 'x2' then oldCrop.x2 = Math.round( scale.l + oldCrop.x1 )
                    when 'y1' then oldCrop.y1 = Math.round( (@Jimg.height() - scale.l) / 2 )
                    when 'y2' then oldCrop.y2 = Math.round( scale.l + oldCrop.y1 )
            else
                if tmp is 'x1' or tmp is 'x2' then oldCrop[tmp] = val*@Jimg.width();
                if tmp is 'y1' or tmp is 'y2' then oldCrop[tmp] = val*@Jimg.height();
            
        
        
        ## instance of image area select: used to force aspect ratio in onSelectChange event
        $ias = div.imgAreaSelect({instance: true});    
        $ias.setOptions({handles: true , onInit: preview, onSelectChange: preview ,  x1: oldCrop.x1 ,y1:oldCrop.y1, x2:oldCrop.x2, y2:oldCrop.y2, show: true, minWidth: 50, minHeight: 50});
        return
   


    # classe che rappresenta la finestra di zoom 
    # @param  {[type]} size      small, medium, etc
    # @param  {[type]} div       il selettore jquery del box di crop
    # @param  {[type]} scale     classe che contiene i parametri delle scale
    # @return {[type]}           [description]

    PrevWindow = (size ,div, scale)->
        ## dimensioni immagine di crop
        this.x = scale.w;
        this.y = scale.h;    
        this.r = scale.r;
        ## identificativo del selettore
        box = div.attr("id") + '-' + size;
        src = div.attr("src");
        ## titolo della finestra
        ## il box che contiene tutte le preview . lo utilizzo per gestire la visualizzazione
        prevBox = $('#prev-container');
        if !prevBox.length
           ## alert('nada')
           prevBox = $('<div/>', {
               'id':'prev-container',
               ## 'style':'cursor:pointer;font-weight:bold;',
           }).insertAfter(div.parent());
        


        ## creo la preview
        ## ho il box prev-container che contiene il titolo e il div con dentro il tag img,
        ## in particolare il div con tag img mi fa da preview, pertanto esso per visualizzare l'immagine non deve contenere altro
        this.Jpre = $('<div><img id="'+box+'" src="'+src+'" style="width:'+scale.w+'px; height:'+scale.h+'px;" /><div>')
            .css({
                ## position: 'relative',
                overflow: 'hidden',
                ## width: scale.l+'px',
                ## height: scale.l+'px', ## opzione fisso a quadrato
                width: scale.w + 'px',
                height: scale.h + 'px'
            })
            ## .prepend(title)
            ## uso parent() perche' li inserisco dopo il div che contiene l'immagine, e non dopo l'immagine stessa
            .appendTo(prevBox);
        ## racchiudo tutto in un box che non ha proprieta
        this.Jpre.wrap('<div class=\'prev-single-container\' style="display:inline;"></div>');
        this.prevSingle = $('.prev-single-container').css({'display':'inline-block'});
        title = $('<div>Preview '+size+'</div>').css({
            'class':"prev-title",
            'style' :"margin-top: 5px, padding: 2px",
            'background-color': 'rgba(70, 144, 214, 0.8)',
            'width' : this.Jpre.width()
        });
        this.Jpre.parent().css({
            ## 'float': 'left', 
            position:'relative', margin: $margin
            }).prepend(title);  
    
    
        ## selettore jquery: DEVE essere inserito solo dopo aver creato l'oggetto DOM
        this.divj = $('#' + box); 
        ## console.log(this.divj)
    
    
        ## lunghezza minima, ovvero il lato della preview
        ## this.k = Math.min(this.x, this.y);
    
        ## modifico la preview
        this.draw = (img, selection)->
            ## ratio rappresenta la %di zoom rispetto alle dimensioni originali
            ## se zoommo di 1/3 (ovvero la selezione rispetto alle dimensioni originali)
            ## allora l'immagine della finestra di preview devono essere triplicate (scleX e scale Y)
            
            ratiox = selection.width / img.width;
            ratioy = selection.height / img.height;
    
            scaleX = this.x / (ratiox || 1); ## nella versione a quadrato ho this.k al numeratore
            ##  scaleY = this.x / (ratioy || 1); ## nella versione a quadrato ho this.k al numeratore
            scaleY = scaleX * this.r;
    
    
            ## adatto l'immagine di previwe
            ## l'immagine
            this.divj.css({
                width: Math.round(scaleX) + 'px',
                height: Math.round(scaleY) + 'px',
                marginLeft: '-' + Math.round( scaleX * selection.x1 / img.width ) + 'px',
                marginTop: '-' + Math.round( scaleY * selection.y1 / img.height ) + 'px'
            });
    
            ## extra non presente nell'impostazione quadrata
            this.Jpre.css({
                'height': Math.round(this.x *selection.height/selection.width) + 'px'
            })

            return
    
        this.remove = ()->
            this.Jpre.parent().remove();
            return

        return


    ## immagine concreta, e oggetto coordinate della selezione, ovvero x1, 
    ##  $check_yet = false;
    preview = (img, selection)->
           
        ## forzo l'aspect ratio in modo che la larghezza non superi l'altezza 
        ## e l'altezza non sia il doppio della larghezza
        if selection.height < selection.width or selection.height > 2*selection.width
            x1 = $ias.getOptions().x1;
            x2 = $ias.getOptions().x2;
            y1 = $ias.getOptions().y1;
            y2 = $ias.getOptions().y2;
            ## $ias.setSelection(selection.x1,selection.y1, selection.x2, selection.y1 + selection.w)
            $ias.setSelection(x1, y1, x2, y2);
            $ias.update()
            return false;
    
        ## disegno le previews
        `for( i in $preWindos){
            $preWindos[i].draw(img, selection);
        }`
        
        ## riempio il form
        normalized = {};
        normalized.x1 = selection.x1 / img.width;
        normalized.x2 = selection.x2 / img.width;
        normalized.y1 = selection.y1 / img.height;
        normalized.y2 = selection.y2 / img.height;
        ## arrotondo a 5 decimali
        for property,value of normalized 
            ## if (object.hasOwnProperty(property)) {
                ## alert(property)
                normalized[property] = Math.round(100000 * normalized[property])/100000;
                ## seleziono l'input che matcha la proprieta', cosi' riempio il form normalizzato
                $Jcrop[property].val(normalized[property]);
            ## }
        
        $ias.setSelection(selection.x1, selection.y1, selection.x2, selection.y2)
        $ias.setOptions({ x1:selection.x1, y1: selection.y1, x2: selection.x2, y2: selection.y2 });
        $ias.update();
        return

    return {
        create: ()->
            return new Gobj()
    }
    
);
