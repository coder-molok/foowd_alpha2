
### CODICE DI PROMEMORIA PER WYSING


# frame = { 
#     el : $('iframe[class*="cke_wys"]').contents().find('body')
#  }

# (frameLoaded = ()->
#     console.log(frame.length)
#     if frame.el.length >= 1
#         # sanitizePaste(frame)
#     else
#         frame.el = $('iframe[class*="cke_wys"]').contents().find('body')
#         setTimeout( frameLoaded, 2000)
# )()

# sanitizePaste = (obj)->
    # el.parent().on 'click', ()->
    #     alert 'click'

    # conservo come promemoria
    # el.parent().on 'pasteee', (e)->
    #     # alert('paste')
    #     # console.log(e)
    #     # alert 'paste'
    #     # e.preventDefault()
    #     # text = (e.originalEvent || e).clipboardData.getData('text/plain') || prompt('Paste something..');
    #     # console.log(text)
    #     # window.document.execCommand('insertText', true, text);
    #      # get content before paste
    #     _this = $(this).find('body')
    #     before = _this.html();
    #     # devo wrappare delle azioni che compie ckeditor...
    #     before = before.replace(/<span data-cke-bookmark.+>.+<\/span>/gi,'')
    #     # alert(before)
    #     console.log('prima: \t:' + before)

    #     setTimeout( ()->
    #         # get content after paste by a 100ms delay
    #         after = _this.html();
    #         # console.log(_this)
    #         # alert(after)
    #         # find the start and end position where the two differ
    #         pos1 = -1;
    #         pos2 = -1;
    #         `for (var i=0; i<after.length; i++) {
    #             if (pos1 == -1 && before.substr(i, 1) != after.substr(i, 1)) pos1 = i;
    #             if (pos2 == -1 && before.substr(before.length-i-1, 1) != after.substr(after.length-i-1, 1)) pos2 = i;
    #         }`
    #         # the difference = pasted string with HTML:
    #         pasted = after.substr(pos1-1, after.length-pos2-pos1+2);
    #         # strip the tags:
    #         replace = pasted.replace(/<[^>]+>/g, '');
    #         # build clean content:
    #         replaced = after.substr(0, pos1-1)+replace+after.substr(pos1-1+pasted.length);
    #         # replace the HTML mess with the plain content
    #         _this.html( replaced );
    #         return;
    #     , 100);
    
    # ordine di trigger: paste textInput , input (viene triggerato da textInput)
    
    # rimpiazzo l'html che il plugin usa come bookmark        
    # rx = replace(/<span data-cke-bookmark.+>.+<\/span>/gi,'')
    
    # purgeHtml = /<[^>]+>/g

    # el = obj.el

    # countChar = el.parent().find()

    # # el.parent().on 'textInput', (e)->
    # #     console.log(e.type)
    # #     console.log(e)
    # #     text.before = $(e.delegateTarget).find('body').html();
    # #     console.log text.before

    # el.parent().on 'input', (e)->
    #     # console.log(e.type)
    #     # console.log(e)
    #     myinput = $(this).find(e.target);

    #     # rimuovo l'html dal testo incollato
    #     myinput.html(myinput.html().replace(purgeHtml, ''))
    #     # alert myinput.html()

    #     ## nel caso volessi implementare controlli successivi
    #     # checkLength = ()->
    #     #     txt = el.html()
    #     #     console.log txt
    #     #     console.log('l: '+ txt.length)

    #     # # alert txt
    #     # setTimeout checkLength, 500