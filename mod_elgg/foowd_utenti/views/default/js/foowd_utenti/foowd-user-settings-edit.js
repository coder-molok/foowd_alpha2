// Generated by CoffeeScript 1.10.0
(function() {
  var indexOf = [].indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  (function(root, factory) {
    if (typeof define === 'function' && define.amd) {
      return define(['elgg', 'jquery', 'foowdFormCheck'], factory);
    } else if (typeof exports === 'object') {
      return module.exports = factory();
    } else {
      return root.returnExports = factory();
    }
  })(this, function() {
    var $, Jform, Jgenre, Jhook, _emailBefore, _usernameBefore, advise, ajaxCheck, ar, checkGenre, elgg, fct, form, genre, needAr, needArOfferente, noNeedAr, setNeed;
    elgg = require('elgg');
    $ = require('jquery');
    Jform = $('.elgg-form-usersettings-save');
    Jform.fadeIn('slow');
    $('.elgg-body').each(function() {
      var html, mod;
      html = $(this).html();
      mod = $(this).closest('.elgg-module');
      $(html).insertAfter(mod);
      return mod.remove();
    });
    $('<label for="name">' + elgg.echo('name') + '</label>').insertBefore($('input[name="name"]'));
    $('[for="name"], [name="name"]').wrapAll('<div></div>');
    $('<label for="email">Email</label>').insertBefore($('input[name="email"]'));
    $('[for="email"], [name="email"]').wrapAll('<div></div>');
    $('p input[name*="password"]').closest('p').css('display', 'none');
    $('select[name="language"]').css({
      'display': 'none'
    });
    $('input[name="method[email]"]').closest('table').css({
      'display': 'none'
    });
    genre = $('[name="js_admin"]').val() === 'amministratore';
    if (genre) {
      advise = $('<div/>').insertAfter($('.elgg-breadcrumbs'));
      advise.html('Salve amministratore, ti ricordo che stai modificando la pagina di un utente.').addClass('foowd-user-settings-admin');
      $('p input[name*="password"]').closest('p').remove();
    }
    if ($('[name="Genre"]').val() === 'evaluating') {
      advise = $('<div/>').insertAfter($('.elgg-breadcrumbs'));
      advise.html('La tua richiesta &egrave; in fase di approvazione. <br/>Di seguito puoi visionare i dati che hai inserito:').addClass('foowd-user-settings-admin-evaluating');
    }
    form = require('foowdFormCheck');
    Jhook = $('#offer-hook');
    Jgenre = $('[name="Genre"]');
    fct = form.factory();
    ar = [];
    _usernameBefore = $('[name="hookUsernameBefore"]').val().toLowerCase();
    _emailBefore = $('[name="hookEmailBefore"]').val().toLowerCase();
    ajaxCheck = function() {
      var url, v;
      v = this.el.val().trim().toLowerCase();
      if (this.key === 'Username' && v === _usernameBefore) {
        this.status = true;
        return;
      }
      if (this.key === 'email' && v === _emailBefore) {
        this.status = true;
        return;
      }
      url = elgg.get_site_url() + 'foowd_utility/user-check?' + this.key.toLowerCase() + '=' + v;
      return $.ajax({
        'url': url,
        'method': 'GET',
        success: (function(_this) {
          return function(resultText, success, xhr) {
            var obj, ret;
            obj = JSON.parse(resultText);
            ret = false;
            if (typeof obj === 'object') {
              console.log(obj);
              if (obj[_this.key.toLowerCase()]) {
                _this.error('Qesto valore e\' gia\' utilizzato. Prova con un altro');
                ret = false;
              } else if (!obj['elgg_validate_' + _this.key.toLowerCase()]) {
                _this.error('Qesto valore e\' in un formato non accettato. Prova con un altro');
                ret = false;
              } else {
                ret = true;
              }
            }
            return _this.status = ret;
          };
        })(this)
      });
    };
    ar.push({
      cls: 'Phone',
      obj: {
        inpt: 'form.elgg-form-usersettings-save [name="Phone"]',
        key: 'Phone',
        el: 'form.elgg-form-usersettings-save [name="Phone"]',
        msg: 'foowd:user:phone:error'
      }
    });
    ar.push({
      cls: 'WebDomain',
      obj: {
        inpt: 'form.elgg-form-usersettings-save [name="Site"]',
        key: 'Site',
        el: 'form.elgg-form-usersettings-save [name="Site"]',
        msg: 'foowd:user:site:error'
      }
    });
    ar.push({
      cls: 'Piva',
      obj: {
        inpt: 'form.elgg-form-usersettings-save [name="Piva"]',
        key: 'Piva',
        el: 'form.elgg-form-usersettings-save [name="Piva"]',
        msg: 'foowd:user:piva:error'
      }
    });
    ar.push({
      cls: 'Text',
      obj: {
        inpt: 'form.elgg-form-usersettings-save [name="Address"]',
        key: 'Address',
        el: 'form.elgg-form-usersettings-save [name="Address"]',
        msg: 'foowd:user:address:error'
      }
    });
    ar.push({
      cls: 'Text',
      obj: {
        inpt: 'form.elgg-form-usersettings-save [name="Company"]',
        key: 'Company',
        el: 'form.elgg-form-usersettings-save [name="Company"]',
        msg: 'foowd:user:company:error'
      }
    });
    ar.push({
      cls: 'Text',
      obj: {
        inpt: 'form.elgg-form-usersettings-save [name="Owner"]',
        key: 'Owner',
        el: 'form.elgg-form-usersettings-save [name="Owner"]',
        msg: 'foowd:user:owner:error'
      }
    });
    ar.push({
      cls: 'Text',
      obj: {
        inpt: 'form.elgg-form-usersettings-save [name="Username"]',
        key: 'Username',
        el: 'form.elgg-form-usersettings-save [name="Username"]',
        msg: 'foowd:user:username:error',
        'afterCheck': ajaxCheck
      }
    });
    ar.push({
      cls: 'Email',
      obj: {
        inpt: 'form.elgg-form-usersettings-save [name="email"]',
        key: 'email',
        el: 'form.elgg-form-usersettings-save [name="email"]',
        msg: 'foowd:user:email:error',
        'afterCheck': ajaxCheck
      }
    });
    ar.push({
      cls: 'Select',
      obj: {
        inpt: 'form.elgg-form-usersettings-save [name="City"]',
        key: 'City',
        el: 'form.elgg-form-usersettings-save [name="City"]',
        msg: 'foowd:user:city:error'
      }
    });
    ar.push({
      cls: 'Integer',
      obj: {
        inpt: 'form.elgg-form-usersettings-save [name="Zipcode"]',
        key: 'Zipcode',
        el: 'form.elgg-form-usersettings-save [name="Zipcode"]',
        msg: 'foowd:user:zipcode:error',
        sizeL: {
          min: 5,
          max: 6
        }
      }
    });
    ar.push({
      cls: 'Price',
      obj: {
        inpt: 'form.elgg-form-usersettings-save [name="MinOrderPrice"]',
        key: 'MinOrderPrice',
        el: 'form.elgg-form-usersettings-save [name="MinOrderPrice"]',
        msg: 'foowd:user:minorderprice:error'
      }
    });
    fct.pushFromArray(ar);
    needAr = ['email', 'Username'];
    needArOfferente = ['Piva', 'Phone', 'Address', 'Company', 'Owner', 'City', 'Zipcode', 'MinOrderPrice'];
    needArOfferente = needAr.concat(needArOfferente);
    noNeedAr = ['Site'];
    setNeed = function(bool) {
      var i, len, localAr, name;
      localAr = bool ? needArOfferente : needAr;
      fct.extraCheck = true;
      for (i = 0, len = localAr.length; i < len; i++) {
        name = localAr[i];
        if ($('[name="' + name + '"]').length <= 0) {
          console.log("manca il campo " + name);
          fct.extraCheck = false;
          break;
        }
      }
      return fct.each(function() {
        var ref, ref1;
        if ((ref = this.key, indexOf.call(localAr, ref) >= 0)) {
          this.needle = true;
        } else if ((ref1 = this.key, indexOf.call(noNeedAr, ref1) >= 0)) {
          this.needle = false;
        } else {
          this.needle = bool;
        }
      });
    };
    setNeed(false);
    fct.extraCheck = true;
    elgg.ajax = false;
    $('input[name="email"], input[name="Username"]').on('mouseout', function(e) {
      var key, ob;
      key = $(this).attr('name');
      ob = fct.getEl(key);
      return ob.inpt.trigger('focusout');
    });
    $('form.elgg-form-usersettings-save').submit(function(e) {
      var check;
      check = true;
      if (!fct.extraCheck) {
        alert('Errore nel form. Si consiglia di ricaricare la pagina');
        check = false;
      }
      if (!check) {
        e.preventDefault();
        return e.stopPropagation();
      }
    });
    form.submit('form.elgg-form-usersettings-save');
    if ($('[name="js_admin"]').val() === 'amministratore' || Jgenre.val() !== 'standard') {
      setNeed(true);
    } else {
      Jhook.css({
        'display': 'none'
      });
      setNeed(false);
      $('#offer-hook').find('[type="text"]').each(function() {
        this.val('');
        return $(this).attr('disabled', true);
      });
    }
    return checkGenre = function() {
      if (this.val() === 'offerente') {

      } else {
        return setNeed(false);
      }
    };
  });

}).call(this);
