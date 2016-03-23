// Generated by CoffeeScript 1.10.0
(function() {
  var extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
    hasProp = {}.hasOwnProperty;

  (function(root, factory) {
    if (typeof define === 'function' && define.amd) {
      return define(['elgg', 'jquery'], factory);
    } else if (typeof exports === 'object') {
      return module.exports = factory();
    } else {
      return root.returnExports = factory();
    }
  })(this, function() {
    var $, Div, Expiration, IframeText, Input, InputFactory, Maxqt, Minqt, Price, Text, __stringToDate, elgg, fac, loom;
    loom = this;
    $ = require('jquery');
    elgg = require('elgg');
    Input = (function() {
      function Input(obj1) {
        var first, inptOn, that;
        this.obj = obj1;
        if (typeof this.obj.el === 'object') {
          this.el = this.obj.el;
          console.log('oggetto');
          console.log(this.obj);
        } else {
          this.el = $(this.obj.el);
        }
        this.inpt = $(this.obj.inpt);
        this.key = this.obj.key;
        that = this;
        first = true;
        this.inpt.on("click focus", function() {
          first = false;
        });
        if (this.obj.trigger != null) {
          $(document).on(this.obj.trigger, function() {
            first = false;
            if (!that.check()) {
              return that.error();
            } else {
              return that.clean();
            }
          });
        }
        this.inpt.on("keyup", (inptOn = function() {
          if (!first) {
            clearTimeout(that.timeout);
            return that.timeout = setTimeout(function() {
              if (!that.check()) {
                return that.error();
              } else {
                return that.clean();
              }
            }, 1000);
          }
        }));

        /*
        @inpt .on "mouseout", ()->
            if !first 
                if not that.check()  
                    that.error()
                else 
                    that.clean()
         */
      }

      Input.prototype.color = function(color) {
        return this.inpt.css({
          "background-color": color
        });
      };

      Input.prototype.check = function() {};

      Input.prototype.error = function() {
        $(".error-" + this.key).remove();
        return $('<span/>', {
          "class": "error-" + this.key,
          "html": elgg.echo(this.msg)
        }).appendTo("label[for*=" + this.key + "]");
      };

      Input.prototype.clean = function() {
        return $(".error-" + this.key).remove();
      };

      return Input;

    })();
    Price = (function(superClass) {
      extend(Price, superClass);

      function Price() {
        return Price.__super__.constructor.apply(this, arguments);
      }

      Price.prototype.check = function() {
        var re, v;
        re = new RegExp(/^\d{1,8}(\.\d{0,2})?$/);
        v = this.el.val().trim();
        if (re.test(v) && v !== '') {
          return true;
        } else {
          return false;
        }
      };

      return Price;

    })(Input);
    Minqt = (function(superClass) {
      extend(Minqt, superClass);

      function Minqt() {
        return Minqt.__super__.constructor.apply(this, arguments);
      }

      Minqt.prototype.check = function() {
        var re, v;
        re = new RegExp(/^\d{1,5}(\.\d{0,3})?$/);
        v = this.el.val().trim();
        if (re.test(v) && v !== '' && parseFloat(v) > 0.0) {
          return true;
        } else {
          return false;
        }
      };

      return Minqt;

    })(Input);
    Maxqt = (function(superClass) {
      extend(Maxqt, superClass);

      function Maxqt() {
        return Maxqt.__super__.constructor.apply(this, arguments);
      }

      Maxqt.prototype.check = function() {
        var Max, Min, re;
        re = new RegExp(/^\d{1,5}(\.\d{0,3})?$/);
        Max = this.el.val().trim();
        Min = $("[name*=Minqt]").val().trim();
        if (Min === '') {
          this.msg = 'Devi prima inserire la quantit&agrave; minima';
          this.el.val('');
          return false;
        }
        if (Max === '') {
          this.msg = 'Devi prima inserire la quantit&agrave; Massima ( 0 se non vuoi impostare un massimo)';
          this.el.val('');
          return false;
        }
        if (!re.test(Max)) {
          this.msg = 'foowd:' + this.key.toLowerCase() + ':error';
          return false;
        }
        if (parseFloat(Max) === 0) {
          return true;
        }
        if (parseFloat(Min) > parseFloat(Max)) {
          this.msg = 'foowd:' + this.key.toLowerCase() + ':error:larger';
          return false;
        }
        return true;
      };

      return Maxqt;

    })(Input);
    Text = (function(superClass) {
      extend(Text, superClass);

      function Text() {
        return Text.__super__.constructor.apply(this, arguments);
      }

      Text.prototype.check = function() {
        var v;
        v = this.el.val().trim();
        if (!v) {
          return false;
        } else {
          return true;
        }
      };

      return Text;

    })(Input);
    IframeText = (function(superClass) {
      extend(IframeText, superClass);

      function IframeText() {
        return IframeText.__super__.constructor.apply(this, arguments);
      }

      IframeText.prototype.check = function() {
        var v;
        v = $('[name="Description"]').val().trim();
        if (v === '') {
          return false;
        } else {
          this.clean();
          return true;
        }
      };

      return IframeText;

    })(Input);
    Expiration = (function(superClass) {
      var printDate;

      extend(Expiration, superClass);

      function Expiration() {
        return Expiration.__super__.constructor.apply(this, arguments);
      }

      Expiration.prototype.check = function() {
        var exp, now;
        if (this.el.val() === '') {
          this.clean();
          return true;
        }
        exp = __stringToDate(this.el.val(), 'yyyy-mm-dd hh:ii:ss');
        now = new Date();
        if (exp > now) {
          this.clean();
          return true;
        } else {
          return false;
        }
      };

      printDate = function(m) {
        var str;
        console.log(m);
        str = m.getUTCFullYear() + "/" + (m.getUTCMonth() + 1) + "/" + m.getUTCDate() + " " + m.getUTCHours() + ":" + m.getUTCMinutes() + ":" + m.getUTCSeconds();
        return str;
      };

      return Expiration;

    })(Input);
    __stringToDate = function(_date, _format) {
      var arg, dateApply, dateItems, formatedDate, i, key, len, lgth, num, start;
      dateItems = ['yyyy', 'mm', 'dd', 'hh', 'ii', 'ss'];
      dateApply = [];
      for (i = 0, len = dateItems.length; i < len; i++) {
        key = dateItems[i];
        start = _format.indexOf(key);
        if (start < 0) {
          num = 0;
        } else {
          lgth = key.length;
          num = _date.substr(start, lgth);
          if (key === 'mm') {
            num = num - 1;
          }
        }
        dateApply.push(parseInt(num));
      }
      arg = dateApply.join(',');
      formatedDate = eval('new Date(' + arg + ')');
      return formatedDate;
    };

    /*
    class Larger extends Input   
        maxInt = $("[name*=Maxqt-integer]")
        maxDec = $("[name*=Maxqt-decimal]")
        minInt = $("[name*=Minqt-integer]")
        minDec = $("[name*=Minqt-decimal]")
    
    
        check: ->
            v = @el.val().trim()
             * se e' vuoto, allora non ci sono problemi!
            if not isFinite(v) or v is ''
                true
            else
                Max = maxInt.val() + '.' +maxDec.val()
                Min = minInt.val() + '.' +minDec.val()
                #alert "#{Min} e #{Max}"
                if Max < Min then false else true
     */
    Div = (function(superClass) {
      extend(Div, superClass);

      function Div() {
        return Div.__super__.constructor.apply(this, arguments);
      }

      Div.prototype.check = function() {
        var v;
        v = document.querySelectorAll(this.obj.el);
        v = v.length;
        if (v > 0) {
          return true;
        } else {
          return false;
        }
      };

      return Div;

    })(Input);
    InputFactory = (function() {
      var JquotaPrev, input, quotaDivs;

      input = {
        'Name': ['Text'],
        'Description': ['IframeText'],
        'Price': ['Price'],
        'Quota': ['Minqt'],
        'Unit': ['Text'],
        'Tag': ['Div', '.search-choice', 'foowd:update:tag'],
        'file': ['Div', '#sorgente'],
        'Expiration': ['Expiration', '[name="Expiration"]', 'foowd:update:expiration']
      };

      function InputFactory() {
        var cls, inpt, key, obj, selector, tmp;
        this.factory = [];
        for (key in input) {
          cls = input[key];
          tmp = cls[0].toString();
          inpt = 'input[name*=' + key + ']';
          selector = '[name*=' + key + ']';
          if (key === 'Description') {
            selector = 'iframe[class*="cke_wys"]';
          }
          if (key === 'Tag') {
            inpt = '.chosen-choices';
          }
          if (key === 'Unit') {
            inpt = 'select[name=Unit]';
          }
          if (cls[1] != null) {
            selector = cls[1];
          }
          obj = {
            "el": selector,
            "key": key.split('-')[0],
            "inpt": inpt
          };
          if (cls[2] != null) {
            obj.trigger = cls[2];
          }
          tmp = eval("new " + tmp + '(' + JSON.stringify(obj) + ')');
          tmp.msg = 'foowd:' + key.toLowerCase() + ':error';
          this.factory.push(tmp);
        }
      }

      quotaDivs = '[name="Quota"], select[name="Unit"], [name="UnitExtra"]';

      JquotaPrev = $('#quota-preview');

      $(quotaDivs).on("change keyup", function() {
        var str;
        str = $('[name="Quota"]').val() + ' ' + $('select[name="Unit"]').val() + ' ' + $('[name="UnitExtra"]').val();
        return JquotaPrev.html(str);
      });

      $(quotaDivs).trigger('change');

      InputFactory.prototype.checkAll = function() {
        var check, i, inpt, len, ref;
        check = true;
        ref = this.factory;
        for (i = 0, len = ref.length; i < len; i++) {
          inpt = ref[i];
          if (!inpt.check()) {
            check = false;
            inpt.error();
          }
        }
        return check;
      };

      return InputFactory;

    })();
    fac = new InputFactory();
    $('form').unbind();
    return $('form').on('submit', function(e) {
      if ($('.foowd-advise-pending').length > 0) {
        e.preventDefault();
        elgg.register_error('Il form e\' bloccato.<br/> Vedi intestazione per dettagli.');
        loom.removeSystemErrorPopup();
      }
      if (typeof prepareInput !== "undefined" && prepareInput !== null) {
        prepareInput(desc);
      }
      if (!fac.checkAll()) {
        e.preventDefault();
        alert('Devi finire di compilare dei campi');
        return $(this).find('[type="submit"]').blur();
      }
    });
  });

}).call(this);
