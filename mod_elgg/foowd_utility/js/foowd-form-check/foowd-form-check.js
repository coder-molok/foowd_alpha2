// Generated by CoffeeScript 1.9.3
var extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

(function(root, factory) {
  if (typeof define === 'function' && define.amd) {
    return define(['jquery', 'elgg'], factory($, elgg));
  }
})(this, function($, elgg) {
  var Div, Email, Input, InputFactory, Maxqt, Minqt, Phone, Piva, Price, Text, WebDomain, loom, mystyle, ret;
  loom = this;
  Input = (function() {
    function Input(obj) {
      var first, that;
      this.obj = obj;
      this.needle = true;
      this.el = $(this.obj.el);
      this.inpt = $(this.obj.inpt);
      this.key = this.obj.key;
      this.msg = this.obj.msg;
      if (typeof this.obj.needle === 'boolean') {
        this.needle = this.obj.needle;
      }
      if (typeof this.obj.afterCheck === 'function') {
        this.afterCheck = this.obj.afterCheck;
      }
      that = this;
      first = true;
      this.inpt.on("click focus", function() {
        first = false;
      });
      if (this.obj.trigger != null) {
        $(document).on(this.obj.trigger, function() {
          first = false;
          return that.action();
        });
      }
      this.inpt.on("focusout mouseout keyup", function() {
        if (!first) {
          return that.action();
        }
      });
    }

    Input.prototype.color = function(color) {
      return this.inpt.css({
        "background-color": color
      });
    };

    Input.prototype.action = function() {
      if (!this.check()) {
        this.error();
      } else {
        this.clean();
      }
      if (this.allCheck()) {
        this.clean();
      }
    };

    Input.prototype.allCheck = function() {
      var status;
      if (!this.need()) {
        status = true;
      } else {
        if (this.check() && typeof this.afterCheck === "function") {
          this.afterCheck.call(this);
          if (this.status != null) {
            status = this.status;
          }
        } else {
          status = this.check();
        }
      }
      return status;
    };

    Input.prototype.check = function() {};

    Input.prototype.need = function() {
      var v;
      if (this.needle) {
        return true;
      } else {
        v = this.el.val().trim();
        if (v !== '') {
          return true;
        } else {
          return false;
        }
      }
    };

    Input.prototype.error = function(msg) {
      if (msg == null) {
        msg = this.msg;
      }
      $(".error-" + this.key).remove();
      return $('<span/>', {
        "class": "error-" + this.key,
        "html": elgg.echo(msg)
      }).appendTo("label[for=" + this.key + "]");
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
      if (Max === '') {
        return true;
      }
      Min = $("[name*=Minqt]").val().trim();
      if (Min === '') {
        this.msg = 'Devi prima inserire la quantit&agrave; massima';
        this.el.val('');
        return false;
      }
      if (!re.test(Max)) {
        this.msg = 'foowd:' + this.key.toLowerCase() + ':error';
        return false;
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
  Phone = (function(superClass) {
    extend(Phone, superClass);

    function Phone() {
      return Phone.__super__.constructor.apply(this, arguments);
    }

    Phone.prototype.check = function() {
      var re, v;
      re = new RegExp(/^\d{9,11}$/);
      v = this.el.val().trim();
      if (re.test(v)) {
        return true;
      } else {
        return false;
      }
    };

    return Phone;

  })(Input);
  WebDomain = (function(superClass) {
    extend(WebDomain, superClass);

    function WebDomain() {
      return WebDomain.__super__.constructor.apply(this, arguments);
    }

    WebDomain.prototype.check = function() {
      var re, v;
      re = new RegExp(/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/);
      v = this.el.val().trim();
      if (re.test(v)) {
        return true;
      } else {
        return false;
      }
    };

    return WebDomain;

  })(Input);
  Piva = (function(superClass) {
    extend(Piva, superClass);

    function Piva() {
      return Piva.__super__.constructor.apply(this, arguments);
    }

    Piva.prototype.check = function() {
      var re, v;
      re = new RegExp(/^[0-9]{11}$/);
      v = this.el.val().trim();
      if (re.test(v)) {
        return true;
      } else {
        return false;
      }
    };

    return Piva;

  })(Input);
  Email = (function(superClass) {
    extend(Email, superClass);

    function Email() {
      return Email.__super__.constructor.apply(this, arguments);
    }

    Email.prototype.check = function() {
      var re, v;
      re = new RegExp(/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i);
      v = this.el.val().trim();
      if (re.test(v)) {
        return true;
      } else {
        return false;
      }
    };

    return Email;

  })(Input);

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
    function InputFactory() {
      this.factory = {};
    }

    InputFactory.prototype.pushFromArray = function(ar) {
      var i, len, results, row, tmp;
      results = [];
      for (i = 0, len = ar.length; i < len; i++) {
        row = ar[i];
        tmp = row.cls;
        tmp = eval(tmp);
        results.push(this.factory[row.obj.key] = new tmp(row.obj));
      }
      return results;
    };

    InputFactory.prototype.checkAll = function() {
      var check, inpt, key, ref;
      check = true;
      ref = this.factory;
      for (key in ref) {
        inpt = ref[key];
        if ((inpt.status != null) && inpt.status) {

        } else if (!inpt.allCheck()) {
          check = false;
          inpt.action();
        }
      }
      return check;
    };

    InputFactory.prototype.getEl = function(el) {
      return this.factory[el];
    };

    InputFactory.prototype.each = function(callback) {
      var inpt, key, ref;
      if (typeof callback !== "function") {
        return false;
      }
      ref = this.factory;
      for (key in ref) {
        inpt = ref[key];
        callback.call(inpt);
      }
      return this.factory;
    };

    return InputFactory;

  })();
  $("head").append("<style id='foowd-form-check-css'></style>");
  mystyle = '[class^="error-"] { color: red; padding: 5px; font-style:italic; }';
  $("#foowd-form-check-css").text(mystyle);
  ret = {};
  ret.factory = function() {
    return this.fac = new InputFactory();
  };
  ret.submit = function(el, callbackPre) {
    return $(el).on('submit', (function(_this) {
      return function(e) {
        var proceed;
        proceed = true;
        if (typeof callbackPre === "function" && !callbackPre()) {
          e.preventDefault();
          return;
        }
        if (!_this.fac.checkAll()) {
          e.preventDefault();
          return alert('Devi finire di compilare dei campi');
        }
      };
    })(this));
  };
  return ret;
});
