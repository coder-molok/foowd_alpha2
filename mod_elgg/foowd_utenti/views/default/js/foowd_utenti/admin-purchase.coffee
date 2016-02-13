
( (root, factory)-> 

    if typeof define is 'function' and define.amd
        # AMD. Register as an anonymous module.
        define(['elgg','jquery', 'foowdServices'], factory);
    else if typeof exports is 'object'
        module.exports = factory();
    else
        root.returnExports = factory();
  
)(this, 


()->


    # elgg = require('elgg')
    $ = require('jquery')
    serv = require('foowdServices')
    # crop = require('crop')
    
    _advise = (obj)->
      $("<div></div>").html(obj.outputMsg).dialog({
        title: obj.titleMsg,
        resizable: false,
        modal: true,
        buttons: {
          "Ok": () ->
           if obj.actionClose is true 
              _actionClose(obj)
            $( this ).dialog( "close" );
            if typeof obj.callbackBtn is 'function' then obj.callbackBtn()
          "Annulla": ()->
            $( this ).dialog( "close" );
        }
      });


    # eseguire chiamata per risolvere il la chiusura
    _actionClose = (obj)->
      console.log('chiudo id '+obj.id)
      serv.purchaseSolve(obj.id).then((data)->
        
        obj.actionClose = false;
        
        console.log(data)
        loom = data.result.api
        if typeof loom.response is 'undefined' 
          obj.outputMsg = 'Errore Chiusura Ordine. Vedere Apache Error Log lati API'
          _advise(obj)
          return
        

        if loom.body.length == 1 and loom.body[0].State == 'solved'
          obj.outputMsg = 'Ordine '+obj.id+' Chiuso con successo!'
          # eventualmente usare una closure
          obj.callbackBtn = ()->
              obj.Jhide.stop().animate({'opacity': '0'}, 2000, ()->
                $(this).remove();
              );

          _advise(obj)

        else
          obj.outputMsg = 'Errore Chiusura Ordine. Vedere Apache Error Log lati API'
          _advise(obj)

      )




    closing = $('table a');
    closing.on 'click', ()->
      id = $(this).attr('data-purchase')
      obj = {
        'outputMsg' : 'Sei sicuro di voler completare la chiusura?'
        'titleMsg': 'conferma chiusura'
        'id': id
        'Jhide': $(this).closest('tr')
        'actionClose': true
        'callbackBtn': null
      }
      _advise(obj)

      # nel caso voglia coprire la colonna con un surrogato di ::after
      # w = $(this).closest('tr').width() + 'px'
      # h = $(this).closest('tr').height() + 'px'
      # console.log(w + ' ' +h)
      # $('<div/>',{
      #   'html' : 'belllaaaaaaa'
      # })
      # .attr('data-locked', id)
      # .css({
      #   position: 'absolute',
      #   'left': 0,
      #   'background-color': 'rgba(0, 128, 0, 0.67)',
      #   'width': w,
      #   'height': h,
      #   'color': 'white',
      #   'font-size': '2em',
      #   'font-weight': 'bold',
      #   'text-align': 'center',
      #   'line-height': h
      # })
      # .appendTo($(this).closest('tr'))


    return {
      plug: 'admin-purchase'
    }
);
