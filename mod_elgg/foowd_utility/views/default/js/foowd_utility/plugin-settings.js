var $root = this;

define(function(require){


var $ = require('jquery');

// il form attuale: per il submit
var $form = $('#foowd_utility-settings');
// container principale
var $startHook = $('#tags-hook');
// array globale in cui salvo il tree dei tags
var $tree = {};
// event: lo uso per l'event propagation
var $evtKey=false;
// textarea in cui salvo e visualizzo l'elenco ordinato dei tags
var $tagsOrder = $('#tags-order');

// NB: anzitutto riempire tree se gia' presenti i valori...

(function _stateCheck(){
  switch(document.readyState){
    case "loading":
      document.onreadystatechange = function (){
        setTimeout(_stateCheck, 1000);
      }
    break;
    case "interactive":
    case "complete": 
      my_start()
    break;
  }
})();

// iter di inizializzazione
function my_start(){
  initBox();

  // nel form se non e' un json, val viene impostato a stringa vuota                          
  var obj = $('#tags').val();
  obj = (obj === '') ? {} : JSON.parse(obj);
  if(typeof obj !== 'object' ) alert('Errore inatteso');
  for(var i in obj){
    var group = i;
    createGroupHead(group);
    for (var j in obj[i] ){
        createGroupBody(group, obj[i][j]);
    }
  }

  // popolo il campo di riferimento per l'ordine dei tags
  populateTagsOrder();

}

function initBox(){
  // <div id="create-group">Crea Gruppo</div>
  // <input type="text" id="input-group" value=""/>

  // <div id="tag-box" >
    
  // </div>
  var Jhook = $('<div/>',{'id':'create-group', 'text':'Crea Gruppo'}).insertAfter($startHook);

  $root.$inputGroup = $('<input/>',{'type':'text', 'id':'input-group'}).insertAfter(Jhook);  // per recuperare il gruppo: gia scritto nel body
  $root.$container = $('<div/>', {'id':'tag-box'}).insertAfter($inputGroup);       // dove inserire tutti i menu
  // schematizzazione dei box (poteva essere implementato come array di array per un multi level)

}


// quando clicco per inserire un nuovo campo
$(document).on('click', '#create-group' , function(){
    createGroupBox();
    $evtKey = false;
});

// quando inserisco un valore di input nei tags
$(document).on('keydown', '[id*="input-"]', function(e){
    // console.log(e);
    if(e.which == 13){
      e.preventDefault();
      // e.stopImmediatePropagation();
      // e.stopPropagation();
      // se e' il crea gruppo
      var group = $(this).attr('data-wrap');
      $evtKey = true;
      if( group === undefined ){
          // group = $(this).val();
          createGroupBox();
      }else{
          createGroupBody(group);
      }
      $evtKey = false;
    }
})

function createGroupBox(){
  var group = $inputGroup.val().trim();

  
  if(!checkInput(group)) return;

  if($('#'+group).length > 0){ 
    alert('i tags non possono essere ripetuti!');
    return;
  }
  // azzero il field
  $inputGroup.val('');
  createGroupHead(group);
}

function createGroupHead(group){

  if( $tree[group] === undefined) $tree[group] = [];

  var Jgroup = $('<div/>', {
                'id' : group,
                // 'class': 'create',
                
              })
              .appendTo($container)
              .append($('<div/>', {
                      'class': 'deletable name-group',// 1 per opzione deletable al click, 2 per style, 
                      'data-wrap' : group, // per il wrapAll
                      'data-remove': group,
                      'text':group

              }))
              .append($('<input/>', {
                    'id':   'input-' + group,   // per lo style e per il click .add
                    'data-wrap' : group, // per il wrapAll
                    // 'class': 'input-single '+group
              }))
              .append($('<span/>', {
                              'class': 'add',
                              'data-wrap' : group, // per il wrapAll
                              'text': 'aggiungi tag'
                            })
              );

    // wrapAll sara' il box dice dove appendere il box creato
    $('[data-wrap='+group+']').wrapAll($('<div id="head-'+group+'"></div>'));
    // metto il focus sull'input appena creato: utile se si inseriscono i tag con l'invio
    $('#input-'+group).focus();

}


// qundo clicco, aggiungo un nuovo gruppo
$(document).on('click', '.add', function(){
      $evtKey = false;
      var group = $(this).attr('data-wrap').trim();
      // var insert = $(this).attr('data-insertAfter')
      createGroupBody(group);
});

function createGroupBody(group, subTag ){
    var single = subTag;
    if( subTag === undefined) single = $('#input-'+group).val().trim();

    if(!checkInput(single)) return;

    if($('[data-remove="single-'+single+'"]').length > 0){ 
      alert('i tags non possono essere ripetuti!');
      return;
    }

    // azzero il valore
    $('#input-'+group).val('');

    // cerco l'ultimo elemento, in quanto aggiungo sempre sul fondo
    var sibling = $('#head-'+group).siblings().last();
    if( sibling.length == 0 ) sibling = $('#head-'+group);

    // recupero l'id , ovvero il nome del gruppo.
    $('<div/>', {
                    'text':single,
                    'class': 'deletable name-single',
                    'data-remove':'single-'+single,
                    'data-group' : group
                  })
                  .insertAfter(sibling);
  
  // aggiungo alla variabile globale
  $tree[group].push(single);
  updateTags();
}


// per eliminare
$(document).on('click', '.deletable', function(){
    var element = $(this).attr('data-remove');
    var div = $('[data-remove='+element+']');
    var isGroup = ($(this).attr('class').indexOf('group') > -1 );


    if(isGroup){
      group = element;
      text = 'Sei sicuro di voler rimuovere tutto il gruppo di tag "'+group+'" ?';
      div = $('#'+group);
    }else{
      group = $(this).attr('data-group');
      text = 'sei sicuro di voler rimuovere questo tag ?';
    }

    if (confirm(text)) {
        

        if(isGroup){
          delete $tree[group];
        }else{
          var index=$tree[group].indexOf($(this).text());
          $tree[group].splice(index, 1);
        }

        div.remove();
    } else {
        // Do nothing!
    }
    updateTags();
    // console.log(JSON.stringify($tree))

});


function checkInput(str){
  var isWord = /^[a-z]+$/.test(str);
   if(!isWord && !$evtKey){
    alert('I tags possono solo essere singole parole formate da caratteri minuscoli');
   }
   return isWord;
}


// aggiorno il campo tags, ovvero quello che voglio salvare!
function updateTags(){
  var obj = {};
  for(var i in $tree){
    obj[i] = {};
    var treeSorted = $tree[i].sort();
    // console.log(treeSorted)
    for(var j in treeSorted){
      obj[i][j]= $tree[i][j];
    }
  }

  $('#tags').val(JSON.stringify(obj));
  // console.log(JSON.stringify(obj));
}

// al primo avvio ricreo sulla base dei tags
function populateTagsOrder(){
  $tagsOrder.each(function(){
    // console.log('populate start')
    // console.log($tree);
    var tagsOrdered = Object.keys($tree).join(', ');
    $(this).val(tagsOrdered)
  });  
}
// funzione per il confronto: la chiamo al submit
function compareTagsOrder(){
  var _check = true;
  $tagsOrder.each(function(){
    // tolgo tutti gli spazi, inclusi nuove linee. Compatibile fintanto che prevedo parole singole
    var order = $(this).val().replace(/(^\s*,)|(,\s*$)/g, "").replace(/[\s]+/g, '').split(',');
    var actualTree = Object.keys($tree)

    // raccolgo le chiavi da confrontare
    var joinKey = order.concat(actualTree);
    // nuova tree
    var tmpTree = {}
    // rimuovo gli elementi duplicati
    joinKey = joinKey.filter(function (item, pos) {return joinKey.indexOf(item) == pos});

    for(var i in joinKey){
      var ord = ( $.inArray(joinKey[i], order) >= 0 );
      var actual = ( $.inArray(joinKey[i], actualTree) >= 0 );
      if(ord && actual){
        // carico nell'ordine scelto
        tmpTree[joinKey[i]] = $tree[joinKey[i]];
      }else{
        // in caso di errore esco dalla funzione
        if(!actual) alert('Non esiste il gruppo: "'+joinKey[i]+'"');
        if(!ord) alert('Non hai inserito "'+joinKey[i]+'" nel campo di ordinamento dei tags.');

        _check = false;
        return;
      }
    }

    if(!_check) return;

    _check = true;
         
    // reimposto la tree
    console.log(tmpTree)
    $tree = tmpTree; 
    // aggiorno il campo tags per il salvataggio
    updateTags();


  });
  return _check;    
}

$form.on('submit', function(e){
  var check = compareTagsOrder();
  // se check e' andato a buon fine allora ritorno true
  if(check) console.log('salvo!')
  if(!check) e.preventDefault();
})


return {};

}); // end define