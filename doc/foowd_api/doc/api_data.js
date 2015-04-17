define({ "api": [
  {
    "type": "post",
    "url": "/offers",
    "title": "create",
    "name": "create",
    "group": "Offers",
    "description": "<p>Crea una nuova offerta.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Name",
            "description": "<p>nome offerta, ovvero il titolo</p> "
          },
          {
            "group": "Parameter",
            "type": "String/html",
            "optional": false,
            "field": "Description",
            "description": "<p>descrizione offerta,</p> "
          },
          {
            "group": "Parameter",
            "type": "Numeric",
            "optional": false,
            "field": "Price",
            "description": "<p>prezzo</p> "
          },
          {
            "group": "Parameter",
            "type": "Numeric",
            "optional": false,
            "field": "Minqt",
            "description": "<p>quantita&#39; minima</p> "
          },
          {
            "group": "Parameter",
            "type": "Numeric",
            "optional": true,
            "field": "Maxqt",
            "description": "<p>quantita&#39; massima</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Tag",
            "description": "<p>lista dei tag</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "Publisher",
            "description": "<p>id dell&#39;offerente</p> "
          }
        ],
        "Response": [
          {
            "group": "Response",
            "type": "Bool",
            "optional": false,
            "field": "response",
            "description": "<p>false, in caso di errore</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "errors",
            "description": "<p>json contenente i messaggi di errore</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"Name\":\"Salumi a Go Go!\",\n  \"Description\":\"una bella cassa di salumi, buona buona\",\n  \"Price\":\"7,25\",\n  \"Minqt\":\"5\",\n  \"Maxqt\":\"20\",\n  \"Tag\":\"cibo, mangiare, salumi, affettati\",\n  \"Created\":\"2015-03-20 19:07:55\",\n  \"Publisher\":\"37\",\n  \"type\":\"create\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiOffer.php",
    "groupTitle": "Offers"
  },
  {
    "type": "post",
    "url": "/offers",
    "title": "delete",
    "name": "delete",
    "group": "Offers",
    "description": "<p>Per eliminare un&#39; offerta.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "Publisher",
            "description": "<p>id dell&#39;offerente</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "Id",
            "description": "<p>id dell&#39;offerta</p> "
          }
        ],
        "Response": [
          {
            "group": "Response",
            "type": "Bool",
            "optional": false,
            "field": "response",
            "description": "<p>false, in caso di errore</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "errors",
            "description": "<p>json contenente i messaggi di errore</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n\t\"Publisher\":\"37\",\n\t\"Id\":\"30\",\n\t\"type\":\"delete\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiOffer.php",
    "groupTitle": "Offers"
  },
  {
    "type": "get",
    "url": "/offers",
    "title": "offerList",
    "name": "offerList",
    "group": "Offers",
    "description": "<p>Per ottenere la lista delle offerte di un dato Publisher.</p> <p>NB: allo stato attuale e&#39; sufficiente utilizzare il metodo SEARCH, secondo l&#39;url <a href=\"http://localhost/api_offerte/public_html/api/offers?Publisher={{Publisher}}&amp;type=search\">http://localhost/api_offerte/public_html/api/offers?Publisher={{Publisher}}&amp;type=search</a></p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "Publisher",
            "description": "<p>id dell&#39;offerente</p> "
          }
        ],
        "Response": [
          {
            "group": "Response",
            "type": "Bool",
            "optional": false,
            "field": "response",
            "description": "<p>false, in caso di errore</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "errors",
            "description": "<p>json contenente i messaggi di errore</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "URL-Example:",
          "content": "http://localhost/api_offerte/public_html/api/offers?type=offerList&Publisher=37",
          "type": "url"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiOffer.php",
    "groupTitle": "Offers"
  },
  {
    "type": "get",
    "url": "/offers",
    "title": "search",
    "name": "search",
    "group": "Offers",
    "description": "<p>Per ottenere la lista delle offerte di un dato Publisher.</p> <p>Strutturato in questo modo, cerca solo le intersezioni dei filtri.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare</p> "
          },
          {
            "group": "Parameter",
            "type": "Mixed",
            "optional": true,
            "field": "qualunque",
            "description": "<p>qualunque colonna. Il valore puo&#39; essere una STRINGA o un ARRAY come stringa-JSON con chiavi &quot;max&quot; e/o &quot;min&quot; (lettere minuscole).</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "order",
            "description": "<p>stringa per specificare l&#39;ordinamento. Il primo elemento e&#39; la colonna php. Si puo&#39; specificare se &#39;asc&#39; o &#39;desc&#39; inserendo uno di questi dopo una virgola. Generalmente saranno Name, Price, Created, Modified</p> "
          },
          {
            "group": "Parameter",
            "type": "Mixed",
            "optional": true,
            "field": "offset",
            "description": "<p>Il valore puo&#39; essere un INTERO per selezionare i primi N elementi trovati o un ARRAY come stringa-JSON con chiavi &quot;page&quot; e &quot;maxPerPage&quot; per sfruttare la paginazione di propel.</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Tag",
            "description": "<p>elenco di tags separati da virgola</p> "
          }
        ],
        "Response": [
          {
            "group": "Response",
            "type": "Bool",
            "optional": false,
            "field": "response",
            "description": "<p>false, in caso di errore</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "errors",
            "description": "<p>json contenente i messaggi di errore</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "URL-Example:",
          "content": "\nhttp://localhost/api_offerte/public_html/api/offers?Publisher={{Publisher}}&type=search&Id={\"min\":2 ,\"max\":109}&Tag=mangiare, cibo&order=Modified, desc",
          "type": "url"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiOffer.php",
    "groupTitle": "Offers"
  },
  {
    "type": "post",
    "url": "/offers",
    "title": "setState",
    "name": "setState",
    "group": "Offers",
    "description": "<p>Modifica lo stato di un&#39;offerta.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "Id",
            "description": "<p>id dell&#39;offerta</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "Publisher",
            "description": "<p>id dell&#39;offerente</p> "
          },
          {
            "group": "Parameter",
            "type": "Enum",
            "optional": false,
            "field": "State",
            "description": "<p>{open,close}: stato dell&#39;offerta</p> "
          }
        ],
        "Response": [
          {
            "group": "Response",
            "type": "Bool",
            "optional": false,
            "field": "response",
            "description": "<p>false, in caso di errore</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "errors",
            "description": "<p>json contenente i messaggi di errore</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n \"Id\":\"88\",\n \"Publisher\":\"5\",\n \"State\": \"close\",\n \"type\":\"setState\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiOffer.php",
    "groupTitle": "Offers"
  },
  {
    "type": "get",
    "url": "/offers",
    "title": "single",
    "name": "single",
    "group": "Offers",
    "description": "<p>Per ottenere l&#39;offerta specifica di un utente. </p> <p>NB: allo stato attuale e&#39; sufficiente utilizzare il metodo SEARCH, secondo l&#39;url <a href=\"http://localhost/api_offerte/public_html/api/offers?Publisher={{Publisher}}&amp;type=search&amp;Id=88\">http://localhost/api_offerte/public_html/api/offers?Publisher={{Publisher}}&amp;type=search&amp;Id=88</a></p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "Publisher",
            "description": "<p>id dell&#39;offerente</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "Id",
            "description": "<p>id dell&#39;offerta</p> "
          }
        ],
        "Response": [
          {
            "group": "Response",
            "type": "Bool",
            "optional": false,
            "field": "response",
            "description": "<p>false, in caso di errore</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "errors",
            "description": "<p>json contenente i messaggi di errore</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "http://localhost/api_offerte/public_html/api/offers?Publisher=37&Id=31&type=single",
          "type": "url"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiOffer.php",
    "groupTitle": "Offers"
  },
  {
    "type": "post",
    "url": "/offers",
    "title": "update",
    "name": "update",
    "group": "Offers",
    "description": "<p>Per aggiornare un&#39; offerta. Sostanzialmente esegue le stesse operazioni di crea().</p> <p>Se non e&#39; specificata la data di modifica, allora viene impostata all&#39;ora attuale.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "Publisher",
            "description": "<p>id dell&#39;offerente</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Name",
            "description": "<p>nome offerta, ovvero il titolo</p> "
          },
          {
            "group": "Parameter",
            "type": "String/html",
            "optional": false,
            "field": "Description",
            "description": "<p>descrizione offerta,</p> "
          },
          {
            "group": "Parameter",
            "type": "Numeric",
            "optional": false,
            "field": "Price",
            "description": "<p>prezzo</p> "
          },
          {
            "group": "Parameter",
            "type": "Numeric",
            "optional": false,
            "field": "Minqt",
            "description": "<p>quantita&#39; minima</p> "
          },
          {
            "group": "Parameter",
            "type": "Numeric",
            "optional": true,
            "field": "Maxqt",
            "description": "<p>quantita&#39; massima</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Tag",
            "description": "<p>lista dei tag</p> "
          },
          {
            "group": "Parameter",
            "type": "Date",
            "optional": true,
            "field": "Created",
            "description": "<p>funzione php: date(&#39;Y-m-d H:i:s&#39;);</p> "
          }
        ],
        "Response": [
          {
            "group": "Response",
            "type": "Bool",
            "optional": false,
            "field": "response",
            "description": "<p>false, in caso di errore</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "errors",
            "description": "<p>json contenente i messaggi di errore</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n \"Id\":\"38\",\n \"Name\":\"Salumi a Go Go!\",\n \"Description\":\"una bella cassa di salumi, ecceziunali veramente!\",\n \"Price\":\"7,56\",\n \"Minqt\":\"3\",\n \"Maxqt\":\"10\",\n \"Tag\":\"cibo, mangiare, latticini\",\n \"Modified\":\"2015-03-20 19:14:17\",\n \"Publisher\":\"37\",\"type\":\"update\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiOffer.php",
    "groupTitle": "Offers"
  },
  {
    "type": "post",
    "url": "/prefer",
    "title": "create",
    "name": "create",
    "group": "Prefer",
    "description": "<p>Crea una nuova offerta, o incrementa/decrementa della quantita&#39; specificata se gia&#39; presente.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "OfferId",
            "description": "<p>id dell&#39;offerta</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "PreferId",
            "description": "<p>id locale dell&#39;utente (non ExternalId)</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "Qt",
            "description": "<p>quantita&#39; da istanziare o da incrementare/decrementare; Se positiva incrementa, altrimenti decrementa.</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n    \"OfferId\":\"92\",\n    \"UserId\": \"5\",\n    \"type\":\"create\",\n    \"Qt\":\"-43\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiPrefer.php",
    "groupTitle": "Prefer"
  },
  {
    "type": "post",
    "url": "/prefer",
    "title": "delete",
    "name": "delete",
    "group": "Prefer",
    "description": "<p>Crea una nuova offerta, o incrementa/decrementa della quantita&#39; specificata se gia&#39; presente.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "OfferId",
            "description": "<p>id dell&#39;offerta</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "PreferId",
            "description": "<p>id locale dell&#39;utente (non ExternalId)</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n    \"OfferId\":\"92\",\n    \"UserId\": \"5\",\n    \"type\":\"create\",\n    \"Qt\":\"-43\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiPrefer.php",
    "groupTitle": "Prefer"
  },
  {
    "type": "get",
    "url": "/offers",
    "title": "search",
    "name": "search",
    "group": "Prefer",
    "description": "<p>Per ottenere la lista delle offerte di un dato Publisher.</p> <p>Strutturato in questo modo, cerca solo le intersezioni dei filtri.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare</p> "
          },
          {
            "group": "Parameter",
            "type": "Mixed",
            "optional": true,
            "field": "qualunque",
            "description": "<p>qualunque colonna. Il valore puo&#39; essere una STRINGA o un ARRAY come stringa-JSON con chiavi &quot;max&quot; e/o &quot;min&quot; (lettere minuscole).</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "order",
            "description": "<p>stringa per specificare l&#39;ordinamento. Il primo elemento e&#39; la colonna php. Si puo&#39; specificare se &#39;asc&#39; o &#39;desc&#39; inserendo uno di questi dopo una virgola. Generalmente saranno Name, Price, Created, Modified</p> "
          },
          {
            "group": "Parameter",
            "type": "Mixed",
            "optional": true,
            "field": "offset",
            "description": "<p>Il valore puo&#39; essere un INTERO per selezionare i primi N elementi trovati o un ARRAY come stringa-JSON con chiavi &quot;page&quot; e &quot;maxPerPage&quot; per sfruttare la paginazione di propel.</p> "
          }
        ],
        "Response": [
          {
            "group": "Response",
            "type": "Bool",
            "optional": false,
            "field": "response",
            "description": "<p>false, in caso di errore</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "errors",
            "description": "<p>json contenente i messaggi di errore</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "URL-Example:",
          "content": "\nhttp://localhost/api_offerte/public_html/api/prefer?OfferId=38&type=search",
          "type": "url"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiPrefer.php",
    "groupTitle": "Prefer"
  },
  {
    "type": "post",
    "url": "/user",
    "title": "create",
    "name": "create",
    "group": "User",
    "description": "<p>Crea una nuovo utente.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Name",
            "description": "<p>nome offerta, ovvero il titolo</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "ExternalId",
            "description": "<p>id Elgg</p> "
          },
          {
            "group": "Parameter",
            "type": "Enum",
            "optional": false,
            "field": "Genre",
            "description": "<p>{standard, offerente}: tipologia utente</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Location",
            "description": "<p>luogo</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n \"type\":\"create\",\n \"Name\":\"gigi\",\n \"Genre\":\"standard\",\n \"Location\": \"torino\",\n \"ExternalId\":\"54\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiUser.php",
    "groupTitle": "User"
  },
  {
    "type": "post",
    "url": "/user",
    "title": "delete",
    "name": "delete",
    "group": "User",
    "description": "<p>Elimina utente.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Name",
            "description": "<p>nome offerta, ovvero il titolo</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "ExternalId",
            "description": "<p>id Elgg</p> "
          },
          {
            "group": "Parameter",
            "type": "Enum",
            "optional": false,
            "field": "Genre",
            "description": "<p>{standard, offerente}: tipologia utente</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Location",
            "description": "<p>luogo</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n \"type\":\"delete\",\n \"ExternalId\":\"54\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiUser.php",
    "groupTitle": "User"
  }
] });