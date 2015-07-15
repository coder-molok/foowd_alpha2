// in elgg come sempre ricordarsi di fare un upgrade della view la prima volta che si crea

// script in versione AMD compatibile.

(function (root, factory) {

    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define([], factory);
    } else if (typeof exports === 'object') {
        // Node. Does not work with strict CommonJS, but
        // only CommonJS-like environments that support module.exports,
        // like Node.
        module.exports = factory();
    } else {
        // Browser globals (root is window)
        root.returnExports = factory();
  }
}(this, function () {

////////////////////////////
// convenzioni:
// gli oggetti e le variabili globali sono precedute dal $
// le costanti sono precedute da _
// i nomi delle classi iniziano con lettera maiuscola
// 
// parametri preview:
// url precaricamento tramite tag a
// guid da passare per salvare l'immagine che verra' croppata
// file input
//
// per il crop:
// image-container , id del contenitore del contenitore dell'immagine, impostato a display none
// image, id del box in cui inserire l'immagine come tag img


/////////////////////////// inizio di tutte le costruzioni ///////////////////////

"use strict";
// var image
// var image-container
// var w
// var sorgente 
// var img-yet  , per il div di upload nel caso di modifica immagine gia' presente

// se l'immagine e' gia' esistente, allora provvedo subito ad inizializzare la funzione
$(window).on('load', function(){

    var img = document.getElementById('image').getElementsByTagName('img')[0];
    if(img){

        // imposto la larghezza a 400
        var w = 400;
        $(img).width(w);
        img.id = 'sorgente';
        
        $img = img;
        $img.height *= w/$img.width;
        $img.width = w;
        start();
    }else{
        // alert('nada');
    }
});


// http://abandon.ie/notebook/simple-file-uploads-using-jquery-ajax
// var urlF = 'http://localhost/ElggProject/elgg-1.10.5/foowd_utility/test/test';
var urlF = document.getElementById('url').href;
// console.log(urlF);

document.getElementById('loadedFile').addEventListener('change', function(e) {

    // controllo sui formati
    if(! this.value.match(/\.(jpg|jpeg|png|gif)$/i) ){
         alert('Sono validi solo formati jpg - jpeg - png - gif');
         this.value = '';
         return;
    }

    // carico il file
    var file = this.files[0];

    // preparo i dati da inviare
    var formData = new FormData();
    formData.append(this.name, file);
    // console.log(JSON.stringify(formData));

    var guid = document.querySelector('input[name=guid]');
    // console.log(guid.value);
    formData.append(guid.name, guid.value);

    // classe per visualizzare una piccola progressbar
    var pop = new LoadPop();
    
    // alert(JSON.stringify(formData));
    // return;
    // proseguo con l'xmlhttprequest
    var xhr = new XMLHttpRequest();

    xhr.addEventListener('progress', function(e) {
        var done = e.position || e.loaded, total = e.totalSize || e.total;
        var percent = Math.floor(done/total*1000)/10;
        if(!isFinite(percent)) percent = 100;
        pop.progress(percent);
        // console.log('xhr progress: ' + (Math.floor(done/total*1000)/10) + '%');
    }, false);
    if ( xhr.upload ) {
        xhr.upload.onprogress = function(e) {
            var done = e.position || e.loaded, total = e.totalSize || e.total;
            var percent = Math.floor(done/total*1000)/10;
            if(!isFinite(percent)) percent = 100;
            pop.progress(percent);
            // console.log('xhr.upload progress: ' + done + ' / ' + total + ' = ' + percent + '%');
        };
    }
    xhr.onreadystatechange = function(e) {
        
                
        if ( 4 == this.readyState ) {
            
            pop.complete();
            
            console.log(['xhr upload complete', e]);
            // console.log(JSON.stringify(xhr.responseText));

            var obj = null;
            try{
            
                var obj = JSON.parse(this.responseText);
                // alert(obj.message);
                // 
            }catch(e){
               alert('invalid json');
            }

            if (obj == null || obj === undefined){
                return;
            }

            var img = new Image();
            // alert(obj.src)
            img.src = obj.preSrc+obj.src;
            img.id = 'sorgente';

            // alert($img.width + ' x ' + $img.height);

            img.onload = function(){

                // assegno alla variablie globale che verra' utilizzata in start();
                $img = this;

                document.getElementById('image-container').style.display = '';
                // console.log('loaded');
                var div = document.getElementById('image');
                // lo svuoto nel caso vi siano altre immagini
                div.innerHTML = '';
                // per il momento adatto soltanto alla largezza
                var w = 400;
                $img.height *= w/$img.width;
                $img.width = w;

                div.appendChild(this);
                // alert($img.width + ' x ' + $img.height);
                
                // per collaborare con gli altri plugin
                $( document ).trigger( "foowd:update:file" );

                start();

            }

            // $('body').append(JSON.stringify(xhr.responseText));
            // alert(xhr.responseText);
        }
    };
    xhr.open('post', urlF, true);
    // xhr.setRequestHeader("Content-Type","multipart/form-data");
    xhr.send(formData);

});



/////////////////////////////////
// oggetti e variabili globali //
/////////////////////////////////

// immagine concreta
var $img = new Image();
// $img.src = document.getElementById('sorgente').getAttribute("src");

// finestre oggetti preview associati alla finestre
var $preWindos = [];

// css
var $margin = '20px';

// oggeto imgAreaSelect: istanza
// gli assegno anche le vecchie variabili
var $ias = {};

// oggetto globale che utilizzo solo in una funzione
var scale = {
    setScale : function(num){
        this.w = Math.round(num*scale.x);
        this.h = Math.round(num*scale.y);
        this.k = Math.min(scale.x, scale.y);
        this.l = Math.min(scale.w, scale.h);

        //post opzione quadrato: la larghezza e' fissa e si adatta l'altezza
        this.r = this.h/this.w;
        this.w = this.l;
        this.h = this.l / this.r;
    },

    setL : function(l1,l2){
        this.l = Math.min(l1, l2);
    }
};


// inizializzo tutto dopo aver caricato l'immagine
// $img.onload = function() {
//  // alert(this.width);
//  start();
//  // $('img[id^="sorgente-"]').parent().css({
//  //  border: '2px solid blue',
//  //  display: 'inline-box'
//  // });
// };


///////////////////////////////////////////////////////////////////
// inizilizzazione:                                              //
// imposto le finestre e la funzione imgAreaSelect, con callback //
///////////////////////////////////////////////////////////////////
function start(){
    
    // setto la scala: il valore piu piccolo corrisponde a 1 e l'altro scala in proporzione
    // uso il piu piccolo in quanto sto usando l'overflow
    var decimals = 100000; // non penso di avere immagini che superino scale dei 1000px
    if($img.width >= $img.height){
        scale.x = Math.round(decimals * $img.width/$img.height)/decimals;
        scale.y = 1;
    }else{
        scale.x = 1;
        scale.y = Math.round(decimals * $img.height/$img.width)/decimals;
    }

    // imposto la finestra di riferimento e prendo la sua sorgente
    var div = $('img#sorgente');

    // pulsanti per fare lo switch delle proporzioni
    var strg = '<div id="ratio"><div data-ratio="2:3">2:3</div><div data-ratio="3:2">3:2<div/></div>';
    $('<div/>',{'html':strg}).css({'position':'absolute'}).insertBefore(div)

    if(!div.length) alert('div not exists');
    var src = div.attr('src');
    if(!src) alert('src not exists');


    // se gia' presenti, elimino le altre finestre di preview
    var i = $preWindos.length;
    while(i--){
        $preWindos[i].remove();
        $preWindos.splice(i);
    }
    

    // costruisco le finestre di preview
    scale.setScale(100);
    $preWindos.push(new PrevWindow('small', div , scale ));

    scale.setScale(250);
    $preWindos.push(new PrevWindow('medium', div , scale ));


    // resetto i dati nel caso avessi gia' caricato un'immagine rimuovendo le classi generate dal plugin
    var x = document.querySelectorAll('[class^=imgareaselect]');
    for (var i = 0; i < x.length; i++) {
        x[i].remove();
    }

    // imposto i dati per l'inizializzazione dello script di "crop"
    scale.setL(div.width(), div.height());

    // recupero eventuali valori di crop iniziali
    var ar = ['x1', 'x2', 'y1', 'y2'];
    var oldCrop = {};
    var ratio ;
    if ($img.width > $img.height){
        ratio = '3:2';
    }else{
        ratio = '2:3';
    }
    var r = [];
    r['2:3']={'h': 0.9, 'w': 0.6};
    r['3:2']={'h': 0.6, 'w': 0.9};

    for(var i in ar){
        var tmp = ar[i];
        var val = $('input[name*='+tmp+']').val();
        if(val === ''){
            // faccio in modo che la finestra sia centrata nell'immagine
            var l = Math.min($img.width, $img.height);
            var x = Math.round( $img.width*r[ratio].w  );
            var y = Math.round( $img.width*r[ratio].h  );
            switch(tmp){
                // case 'x1': oldCrop.x1 = Math.round( ($img.width - scale.l) / 2 ); break;
                // case 'x2': oldCrop.x2 = Math.round( scale.l + oldCrop.x1 ); break;
                // case 'y1': oldCrop.y1 = Math.round( ($img.height - scale.l) / 2 ); break;
                // case 'y2': oldCrop.y2 = Math.round( scale.l + oldCrop.y1 ); break;
                case 'x1': oldCrop.x1 = Math.round( (l - x) / 2 ); break;
                case 'x2': oldCrop.x2 = Math.round( x + oldCrop.x1 ); break;
                case 'y1': oldCrop.y1 = Math.round( (l - y) / 2 ); break;
                case 'y2': oldCrop.y2 = Math.round( y + oldCrop.y1 ); break;
            }

        }else{
            if(tmp == 'x1' || tmp == 'x2')  oldCrop[tmp] = val*$img.width;
            if(tmp == 'y1' || tmp == 'y2')  oldCrop[tmp] = val*$img.height;
        }
    }

    // instance of image area select: used to force aspect ratio in onSelectChange event
    $ias = div.imgAreaSelect({instance: true});    
    //$ias.setOptions({/* aspectRatio: '1:1',*/ handles: true , onInit: preview, onSelectChange: preview ,  x1: oldCrop.x1 ,y1:oldCrop.y1, x2:oldCrop.x2, y2:oldCrop.y2, show: true, minWidth: 30, minHeight: 30});    
    $ias.setOptions({aspectRatio: '2:3', handles: true , onInit: preview, onSelectChange: preview ,  x1: oldCrop.x1 ,y1:oldCrop.y1, x2:oldCrop.x2, y2:oldCrop.y2, show: true, minWidth: 30, minHeight: 30});    
    
    // per fare lo switch delle proporzioni
    $('div[data-ratio]').on('click', function(){
        
        var ratio = $(this).attr('data-ratio')

        

        // $ias.update()
        // var x1 = $ias.getOptions().x1;
        // var x2 = $ias.getOptions().x2;
        // var y1 = $ias.getOptions().y1;
        // var y2 = $ias.getOptions().y2;
        // var w = x2-x1;
        // var h = y2-y1;
        // var xc = (x1+x2)/2
        // var yc = (y1+y2)/2

        var tmp = {}
        // if(ratio > 1){ // ovvero se e' piu largo che lungo
        //     console.log('>1')
        //     tmp.x = Math.max(w,h);
        //     tmp.y = Math.min(w,h);
        // }else{
        //     console.log('<1')
        //     tmp.x = Math.min(w,h);
        //     tmp.y = Math.max(w,h);
        // }
        
        
        // x1 = xc - tmp.x/2
        // x2 = x1 + tmp.x
        // y1 = yc - tmp.y/2
        // y2 = y1 + tmp.y
        
        var l = Math.min($img.width, $img.height);


        var x = Math.round( l*r[ratio].w  );
        var y = Math.round( l*r[ratio].h  );
        tmp.x1 = Math.round( ($img.width - x) / 2 ); 
        tmp.x2 = Math.round( x + tmp.x1 ); 
        tmp.y1 = Math.round( ($img.height - y) / 2 );
        tmp.y2 = Math.round( y + tmp.y1 ); 
        
        tmp.width = tmp.x2-tmp.x1;
        tmp.height = tmp.y2-tmp.y1;
        
        // console.log(JSON.stringify(tmp))
        $ias.setSelection(tmp.x1, tmp.y1, tmp.x2, tmp.y2);
        // var obj = {aspectRatio: ratio, x1:tmp.x1, x2:tmp.x2, y1:tmp.y1, y2:tmp.y2}
        // console.log(JSON.stringify(obj))
        $ias.setOptions({aspectRatio: ratio, x1:tmp.x1, x2:tmp.x2, y1:tmp.y1, y2:tmp.y2})
        $ias.update();
        
        // aggiorno le preview    
        preview($img, tmp);
    
    })
}   

/**
 * classe che rappresenta la finestra di zoom
 * 
 * @param  {[type]} size      small, medium, etc
 * @param  {[type]} div       il selettore jquery del box di crop
 * @param  {[type]} scale     classe che contiene i parametri delle scale
 * @return {[type]}           [description]
 */
var PrevWindow = function(size ,div, scale){
    // dimensioni immagine di crop
    this.x = scale.w;
    this.y = scale.h;    
    this.r = scale.r;
    
    // identificativo del selettore
    var box = div.attr("id") + '-' + size;
    var src = div.attr("src");
    // titolo della finestra
    
    // il box che contiene tutte le preview . lo utilizzo per gestire la visualizzazione
    var prevBox = $('#prev-container');
    if(!prevBox.length) {
        // alert('nada')
        prevBox = $('<div/>', {
            'id':'prev-container',
            // 'style':'cursor:pointer;font-weight:bold;',
        }).insertAfter(div.parent());
    }


    // creo la preview
    // ho il box prev-container che contiene il titolo e il div con dentro il tag img,
    // in particolare il div con tag img mi fa da preview, pertanto esso per visualizzare l'immagine non deve contenere altro
    this.Jpre = $('<div><img id="'+box+'" src="'+src+'" style="width:'+scale.w+'px; height:'+scale.h+'px;" /><div>')
        .css({
            // position: 'relative',
            overflow: 'hidden',
            // width: scale.l+'px',
            // height: scale.l+'px', // opzione fisso a quadrato
            width: scale.w + 'px',
            height: scale.h + 'px'
        })
        // .prepend(title)
        // uso parent() perche' li inserisco dopo il div che contiene l'immagine, e non dopo l'immagine stessa
        .appendTo(prevBox);
     // racchiudo tutto in un box che non ha proprieta
     this.Jpre.wrap('<div class=\'prev-single-container\' style="display:inline;"></div>');
     this.prevSingle = $('.prev-single-container').css({'display':'inline-block'});
     var title = $('<div>Preview '+size+'</div>').css({
        'class':"prev-title",
         'style' :"margin-top: 5px, padding: 2px",
         'background-color': 'rgba(70, 144, 214, 0.8)',
         'width' : this.Jpre.width()
     });
     this.Jpre.parent().css({
        // 'float': 'left', 
        position:'relative', margin: $margin}).prepend(title);




    // selettore jquery: DEVE essere inserito solo dopo aver creato l'oggetto DOM
    this.divj = $('#' + box); 
    // console.log(this.divj)


    // lunghezza minima, ovvero il lato della preview
    // this.k = Math.min(this.x, this.y);

    // modifico la preview
    this.draw = function(img, selection){
        // ratio rappresenta la %di zoom rispetto alle dimensioni originali
        // se zoommo di 1/3 (ovvero la selezione rispetto alle dimensioni originali)
        // allora l'immagine della finestra di preview devono essere triplicate (scleX e scale Y)
        
        var ratiox = selection.width / img.width;
        var ratioy = selection.height / img.height;

        var scaleX = this.x / (ratiox || 1); // nella versione a quadrato ho this.k al numeratore
        // var scaleY = this.x / (ratioy || 1); // nella versione a quadrato ho this.k al numeratore
        var scaleY = scaleX * this.r;


        // adatto l'immagine di previwe
        // l'immagine
        this.divj.css({
            width: Math.round(scaleX) + 'px',
            height: Math.round(scaleY) + 'px',
            marginLeft: '-' + Math.round( scaleX * selection.x1 / img.width ) + 'px',
            marginTop: '-' + Math.round( scaleY * selection.y1 / img.height ) + 'px'
        });

        // extra non presente nell'impostazione quadrata
        this.Jpre.css({
            height: Math.round(this.x *selection.height/selection.width) +'px'
        });
    };

    this.remove = function(){
        this.Jpre.parent().remove();
    }
}


// immagine concreta, e oggetto coordinate della selezione, ovvero x1, 
// var $check_yet = false;
function preview(img, selection) {
    
    // console.log($ias.getOptions().x1);
    // console.log(JSON.stringify(selection))
    // console.log(JSON.stringify(img))

    // forzo l'aspect ratio in modo che la larghezza non superi l'altezza 
    // e l'altezza non sia il doppio della larghezza
    // if(selection.height < selection.width || selection.height > 2*selection.width){
    //     var x1 = $ias.getOptions().x1;
    //     var x2 = $ias.getOptions().x2;
    //     var y1 = $ias.getOptions().y1;
    //     var y2 = $ias.getOptions().y2;
    //     // $ias.setSelection(selection.x1,selection.y1, selection.x2, selection.y1 + selection.w)
    //     $ias.setSelection(x1, y1, x2, y2);
    //     $ias.update()
    //     return false;
    // }

    // disegno le previews
    for(var i in $preWindos){
        $preWindos[i].draw(img, selection);
    }
    
    // riempio il form
    var normalized = {};
    normalized.x1 = selection.x1 / img.width;
    normalized.x2 = selection.x2 / img.width;
    normalized.y1 = selection.y1 / img.height;
    normalized.y2 = selection.y2 / img.height;
    // arrotondo a 5 decimali
    for (var property in normalized) {
        // if (object.hasOwnProperty(property)) {
            // alert(property)
            normalized[property] = Math.round(100000 * normalized[property])/100000;
            // seleziono l'input che matcha la proprieta', cosi' riempio il form normalizzato
            $('input[name*='+property+']').val(normalized[property]);
        // }
    }
    
    // solo se devo controllare dinamicamente per forzare l'aspect ration
    // $ias.setSelection(selection.x1, selection.y1, selection.x2, selection.y2)
    // $ias.setOptions({ x1:selection.x1, y1: selection.y1, x2: selection.x2, y2: selection.y2 });
    // $ias.update();

}


// vedere foowd_offerte.css per gli elementi
var LoadPop =  function(){

    // lightbox
    this.div=document.createElement("div");
    this.div.className = 'foowd-lightbox';
    document.body.appendChild(this.div);

    // container progress
    this.box = document.createElement('div');
    this.box.className='progress-container';
    this.div.appendChild(this.box);

    // la barra progress 
    this.x = document.createElement("PROGRESS");
    this.x.className = 'progress-bar';
    this.x.max = 100;
    this.x.value = 0;
    this.box.appendChild(this.x);

    // la scritta 
    this.t = document.createElement("span");
    this.t.className = 'progress-value';
    this.t.innerHTML = '0 %';
    this.box.appendChild(this.t);
    

    // infine sistemo il box centrandolo
    this.box.style.left = ($wSize.w - this.box.offsetWidth)/2 +'px';
    this.box.style.top = ($wSize.h - this.box.offsetHeight)/2 +'px';
    // this.x.insertAdjacentHTML( 'beforeBegin', '<br/>' );

    

    this.progress = function(percent){
        this.x.value = Math.floor(percent);
        this.t.innerHTML = Math.floor(percent)+' %';
    };

    this.complete = function(){
        // console.log('done')
        this.div.remove();
    };

}


// dimensioni finestra
var $wSize = (function(){
    var w = window,
        d = document,
        e = d.documentElement,
        g = d.getElementsByTagName('body')[0],
        x = w.innerWidth || e.clientWidth || g.clientWidth,
        y = w.innerHeight|| e.clientHeight|| g.clientHeight;
    return{
        'w' : x,
        'h' : y
    }
})()


/////////////////////////// fine di tutte le costruzioni ///////////////////////	

    return  {

    };
}));