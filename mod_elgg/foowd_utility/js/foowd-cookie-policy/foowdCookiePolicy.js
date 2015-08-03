// if the module has no dependencies, the above pattern can be simplified to
// script raccolto e implementato da simone scardoni al fine di avere una coerente gestione dei cookies e degli eventi annessi
(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node. Does not work with strict CommonJS, but
        // only CommonJS-like environments that support module.exports,
        // like Node.
        module.exports = factory(require['jquery']);
    } else {
        // Browser globals (root is window)
        root.InPolicy = factory(root.jQuery);
  }
}(this, function ($) {

    return {
    
     cookie_name: 'in_cookie_policy_accepted',
     cookies_list: [],

     // obj.cookies: cookies array
     // obj.link: link to cookie policy
     init: function(obj){

      this.callbacks(obj);

       if(obj.cookies == null || obj.cookies == 'undefined' ) obj.cookies = [];
       /////
       this.cookies_list = obj.cookies;
       this.acceptedActions();
        if(this.policyAccepted()){
          // triggero sia su window che document
            $(window).add(document).trigger('cookieAccepted');
        }
        this.obj = {}
        for(var i in obj) this.obj[i] = obj[i];
      /////
       var show = !this.policyAccepted();
       if(show){
         for(var i=0; i < this.cookies_list.length; i++){
           var cookie_name = this.cookies_list[i];
           if(!this.getCookie(cookie_name)){
             show = true;
             break;
           } else {
             show = false;
           }
         }
       }
       if(show) this.showBar();
       $('#close-cookie-bar').on('click', $.proxy(function(event){
         event.preventDefault();
         this.acceptPolicy();
       }, this));
       // $(window).on('scroll', $.proxy(function(e){
       //    console.log(e)
       //        console.log('show')
       //   if($('[data-skip-allow="true"]').length == 0)
       //     this.acceptPolicy();
       // }, this));
       $(window).on('click', $.proxy(function(event){
        // list of elements on click doesn't work: that's as not accept
         if($(event.target).attr("id") != 'cookie-policy-link' && $(event.target).attr('data-skip-allow') != 'true' && $(event.target).parents('[data-skip-allow="true"]').length == 0)
           this.acceptPolicy();
       }, this));
     },

     getCookie: function(c_name){
       var i, x, y, ARRcookies = document.cookie.split(";");
       for(i = 0; i < ARRcookies.length; i++){
         x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
         y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
         x = x.replace(/^\s+|\s+$/g, "");
         if(x == c_name){
           return unescape(y);
         }
       }
     },

     setCookie: function(c_name, value){
       var exdate = new Date();
       exdate = new Date(exdate.getTime() + (24*60*60000*365*20)); // 20 anni
       var c_value = escape(value) + "; expires=" + exdate.toGMTString();
       document.cookie = c_name + "=" + c_value;
     },

     removeCookie: function(){
       document.cookie.split(";")
         .forEach(function (c) {
           document.cookie = c.replace(/^ +/, "")
             .replace(/=.*/, "=;expires=" + new Date()
             .toUTCString() + ";path=/");
         });
       localStorage.clear();
     },

     policyAccepted: function(){
       var cpa = this.getCookie(this.cookie_name);
       return cpa ? true : false;
     },

     acceptPolicy: function(){
       if(!this.policyAccepted()){
        // console.log(this.policyAccepted())
         this.setCookie(this.cookie_name, 'yes');
         $(window).add(document).trigger('cookieAccepted');
         this.hideBar();
       }
     },

     callbacks: function(obj){
      var that = this;
      if (typeof obj.onCookieAccepted === "function") { 
        $(window).add(document).on('cookieAccepted', function(){ 
          obj.onCookieAccepted();
        }); 
      }
      if (typeof obj.onCookieAcceptedOnce === "function") { 
        $(window).add(document).one('cookieAccepted', function(){ 
          if (that.onCookieAcceptedOnce === undefined){
            obj.onCookieAcceptedOnce(); 
            that.onCookieAcceptedOnce = true;
          }
        });
      }
     },

     showBar: function(){


       Jbar = $('<div/>',{
           id:     'cookie-bar',
           style:  "display: block;",
           html:   '<h4>Informativa<a id="close-cookie-bar">x</a></h4>'+
                   '<p>Questo sito o gli strumenti terzi da questo utilizzati si avvalgono di cookie necessari al funzionamento ed utili alle finalità illustrate nella cookie policy. Se vuoi saperne di più o negare il consenso a tutti o ad alcuni cookie, consulta la <a href="' + this.obj.link + '" id="cookie-policy-link">cookie policy</a>.<br/>\
                    Chiudendo questo banner, <!-- scorrendo questa pagina, --> cliccando su un link o proseguendo la navigazione in altra maniera, acconsenti all’uso dei cookie.</p>'
       }).insertBefore('body');

       $('#cookie-bar').css({
            'display': 'none',
            'position': 'fixed',
            'top': '0',
            'left': '0',
            'width': '100%',
            'padding': '20px',
            'background-color': '#C81063',
            'color': '#F6F6F6',
            'z-index': '10000',
            '-webkit-box-sizing': 'border-box',
            '-moz-box-sizing': 'border-box',
            'box-sizing': 'border-box',
          });

       Jbar.fadeIn();

       $('#cookie-bar h4').css({
             'font-size': '18px',
             'margin': '0 0 10px 0',
             'position': 'relative',
             'text-align': 'left',
             'font-weight': 'bold',
             'color': '#ffffff'
           })
           $('#cookie-bar h4 a').css({
             color: '#F6F6F6',
             'font-weight': 'normal',
             cursor: 'pointer',
             position: 'absolute',
             right: '0',
             top: '0',
           });
           $('#cookie-bar p').css({
             'font-size': '12px',
             'line-height': '18px',
              'text-indent': '0'
           });
           $('#cookie-bar p a').css({
             color: '#F6F6F6',
             'text-decoration': 'underline',
           });
       
     },

     hideBar: function(){
       $("#cookie-bar").fadeOut();
     },

     acceptedActions: function(){
      
        var that = this;
        var containerFunc = function(){
          that.restoreSRC();
          that.restoreScript();
        }

        $(document).on('ready cookieAccepted _inp_src_activate _inp_script_activate', function(e){
          // console.log('inside acceptedActions');
          if(that.policyAccepted()){
            containerFunc();
          }
        })
     },


     restoreScript: function(){
      $('._inp_script_activate').each(function(){
        if($(this).attr('type') === 'text/javascript') return;

        $(this).attr({'type':'text/javascript'});
        eval($(this).text());
        // $(this).replaceWith(eval($(this).text()))

      });
     },

     restoreSRC: function(){

      $('._inp_src_activate').each(function(){

        if($(this).attr('src') !== '') return;

        var src = $(this).attr('inpolicy-src');
        if(src){
          // console.log(src)
          $(this).attr({'src': src});
        }

      });

     },

     test: function(){
      console.log('Test correct InPolicy Load');
     }  

   
      
    };
}));