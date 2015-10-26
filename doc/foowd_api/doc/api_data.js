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
            "type": "String",
            "optional": true,
            "field": "Id",
            "description": "<p>se il metodo e&#39; update o create, allora l&#39;id dell&#39;offerta</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "errors",
            "description": "<p>json contenente i messaggi di errore</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "body",
            "description": "<p>json contenente i parametri da ritornare in funzione della richiesta</p> "
          },
          {
            "group": "Response",
            "type": "String",
            "optional": true,
            "field": "msg",
            "description": "<p>messaggi ritornati</p> "
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
            "type": "String",
            "optional": true,
            "field": "Id",
            "description": "<p>se il metodo e&#39; update o create, allora l&#39;id dell&#39;offerta</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "errors",
            "description": "<p>json contenente i messaggi di errore</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "body",
            "description": "<p>json contenente i parametri da ritornare in funzione della richiesta</p> "
          },
          {
            "group": "Response",
            "type": "String",
            "optional": true,
            "field": "msg",
            "description": "<p>messaggi ritornati</p> "
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
    "title": "group",
    "name": "group",
    "group": "Offers",
    "description": "<p>Per ottenere la lista delle offerte mediante filtri, in particolare cerca solo le intersezioni dei filtri.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare (group)</p> "
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "OfferId",
            "description": "<p>Id dell&#39;offerta</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "ExternalId",
            "description": "<p>stringa formata da uno o piu Id utenti (Id lato elgg) separati da virgola. Per ciascun utente ritorna la sua preferenza su tale offerta.</p> "
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
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "body",
            "description": "<p>json contenente i parametri da ritornare in funzione della richiesta. Il parametro prefer impostato nel ritorno contiene eventuali preferenze che metchano gli ExternalId passati con la chiamata. I dati relativi agli Id (UserId, Publisher, ExternalId, etc.) sono ritornati come elggId.</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "URL-Example:",
          "content": "\n{{host}}offer?type=group&OfferId=1&ExternalId=52,37",
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
    "description": "<p>Per ottenere la lista delle offerte mediante filtri, in particolare cerca solo le intersezioni dei filtri.</p> ",
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
            "type": "Mixed",
            "optional": true,
            "field": "match",
            "description": "<p>Stringa-JSON le cui chiavi sono le colonne del DB e i valori sono singole parole separate da spazi o virgole.</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Tag",
            "description": "<p>elenco di tags separati da virgola, o stringa di lunghezza nulla &#39;&#39;</p> "
          },
          {
            "group": "Parameter",
            "type": "Str/Num",
            "optional": true,
            "field": "ExternalId",
            "description": "<p>numero intero o sequenza di interi separati da virgola. Rappresenta/no id dell&#39;utente: per ogni offerta ritornata, il campo &quot;prefer&quot; sara&#39; riempito con le preferenze della singola offerta che matchano gli id ivi passati.</p> "
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
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "body",
            "description": "<p>json contenente i parametri da ritornare in funzione della richiesta. Il parametro prefer impostato nel ritorno contiene eventuali preferenze che metchano gli ExternalId passati con la chiamata.</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "body-totalQt",
            "description": "<p>ogni preferenza ritornata contiene la Quantinta&#39; totale ad essa associata. 0 nel caso non vi siano preferenze espresse per essa, o qualora valgano effettivamente zero.</p> "
          },
          {
            "group": "Response",
            "type": "Array/json",
            "optional": true,
            "field": "body-prefer",
            "description": "<p>La/LE preferenza/e espressa/e dall&#39;utente riconosciuto tramite il parametro ExternalId passato durante la chiamata. Se non presente, allora e&#39; un array vuoto</p> "
          },
          {
            "group": "Response",
            "type": "String",
            "optional": true,
            "field": "msg",
            "description": "<p>messaggi ritornati</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "URL-Example:",
          "content": "\nhttp://localhost/api_offerte/public_html/api/offers?Publisher={{Publisher}}&type=search&Id={\"min\":2 ,\"max\":109}&Tag=mangiare, cibo&order=Modified, desc&ExternalId=52,37",
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
            "type": "String",
            "optional": true,
            "field": "Id",
            "description": "<p>se il metodo e&#39; update o create, allora l&#39;id dell&#39;offerta</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "errors",
            "description": "<p>json contenente i messaggi di errore</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "body",
            "description": "<p>json contenente i parametri da ritornare in funzione della richiesta</p> "
          },
          {
            "group": "Response",
            "type": "String",
            "optional": true,
            "field": "msg",
            "description": "<p>messaggi ritornati</p> "
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
            "type": "String",
            "optional": true,
            "field": "Id",
            "description": "<p>se il metodo e&#39; update o create, allora l&#39;id dell&#39;offerta</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "errors",
            "description": "<p>json contenente i messaggi di errore</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "body",
            "description": "<p>json contenente i parametri da ritornare in funzione della richiesta</p> "
          },
          {
            "group": "Response",
            "type": "String",
            "optional": true,
            "field": "msg",
            "description": "<p>messaggi ritornati</p> "
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
    "description": "<p>Crea una nuova offerta (state &quot;newest&quot;), o incrementa/decrementa della quantita&#39; l&#39;offerta con stato (&#39;pending&#39; o &#39;newest&#39;) lasciando inalterate quelle in status &#39;solved&#39;.</p> ",
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
            "field": "ExternalId",
            "description": "<p>id elgg dell&#39;utente</p> "
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
            "field": "ExternalId",
            "description": "<p>id elgg dell&#39;utente</p> "
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
    "url": "/prefer",
    "title": "search",
    "name": "search",
    "group": "Prefer",
    "description": "<p>Oltre a svolgere una ricerca nella tabella preferenze, ritorna anche il parametro extra &quot;<strong>Offer</strong>&quot; contenente l&#39;offerta (in formato JSON) a cui ciascuna preferenza si riferisce.</p> <p>Strutturato in questo modo, cerca solo le intersezioni dei filtri.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>search</p> "
          },
          {
            "group": "Parameter",
            "type": "Str/Num",
            "optional": true,
            "field": "ExternalId",
            "description": "<p>numero intero o sequenza di interi separati da virgola</p> "
          },
          {
            "group": "Parameter",
            "type": "Mixed",
            "optional": true,
            "field": "State",
            "description": "<p>Puo&#39; essere &#39;all&#39; se non voglio filtrare per stato, &#39;editable&#39; se sono interessato agli stati &#39;pending&#39; o &#39;newest&#39;, oppure elenco di stati reali (es. pending, newest, solved) separati con virgola.<br/> Se non specificato di default e&#39; impostato a &quot;editable&quot;.</p> "
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
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "body",
            "description": "<p>json contenente i parametri da ritornare in funzione della richiesta</p> "
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": true,
            "field": "body-offer",
            "description": "<p>ciascuna preferenza ritornata contiene il parametro Offer: un JSON con tutti i dati relativi all&#39;offerta a cui si riferisce la preferenza</p> "
          },
          {
            "group": "Response",
            "type": "String",
            "optional": true,
            "field": "msg",
            "description": "<p>messaggi ritornati</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "URL-Example:",
          "content": "\nhttp://localhost/api_offerte/public_html/api/prefer?OfferId=38&type=search&ExternalId=37,52&State=newest,solved",
          "type": "url"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiPrefer.php",
    "groupTitle": "Prefer"
  },
  {
    "type": "get",
    "url": "/user",
    "title": "commonOffers",
    "name": "commonOffers",
    "group": "User",
    "description": "<p>Trova le offerte comuni a un gruppo di utenti.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare (commonOffers)</p> "
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "ExternalId",
            "description": "<p>gruppo di Id (separati da virgola) dei quali si vogliono trovare le offerte comuni e non</p> "
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
          },
          {
            "group": "Response",
            "type": "String/json",
            "optional": false,
            "field": "body",
            "description": "<p>json contenente i parametri da ritornare in funzione della richiesta. Il parametro offers contiene la proprieta&#39; &quot;friends&quot;: array contenente le preferenze matchanti la lista di ExternalId</p> "
          },
          {
            "group": "Response",
            "type": "array",
            "optional": true,
            "field": "body-offers",
            "description": "<p>array aggiunto a ciascuna offerta e contenente le preferenze degli id matchanti con l&#39;elenco ExternalId</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n \"type\":\"create\",\n \"ExternalId\":\"54,63\"\n}",
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
    "title": "create",
    "name": "create",
    "group": "User",
    "description": "<p>Crea un nuovo utente.</p> ",
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
            "description": "<p>nome dell&#39;utente</p> "
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
            "type": "Integer",
            "optional": false,
            "field": "ExternalId",
            "description": "<p>id Elgg</p> "
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
  },
  {
    "type": "post",
    "url": "/user",
    "title": "search",
    "name": "search",
    "group": "User",
    "description": "<p>Restituisce &quot;response false&quot; se l&#39;ExternalId passato non corrisponde ad alcun utente,                     altrimenti ritorna true e il Genere dell&#39;utente</p> ",
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
            "field": "ExternalId",
            "description": "<p>id Elgg</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "return",
            "description": "<p>elenco dei campi da ritornare (PHP name di Propel), separati dalla virgola</p> "
          }
        ],
        "Response": [
          {
            "group": "Response",
            "type": "String",
            "optional": true,
            "field": "Image",
            "description": "<p>l&#39;immagine salvata nel DB. Questo stream viene ritornato come base64_encode.</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n \"type\":\"search\",\n \"ExternalId\":\"54\",\n \"return\":\"Description,Image\"\n}",
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
    "title": "update",
    "name": "update",
    "group": "User",
    "description": "<p>Aggiorno dati utente: tutti tranne l&#39;ExternalId.</p> ",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>metodo da chiamare: update</p> "
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
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Description",
            "description": "<p>Descrizione dell&#39;utente</p> "
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "Image",
            "description": "<p>Immagine. Deve essere uno stream base64_encode, in particolare ottenibile mediante &quot;base64_encode(stream_get_contents(fopen(&quot;immagine.jpg&quot;,&quot;rb&quot;)))&quot;</p> "
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n \"type\":\"update\",\n \"Name\":\"gigi\",\n \"Genre\":\"standard\",\n \"Location\": \"torino\",\n \"ExternalId\":\"54\",\n \"Description\":\"Sono superbellissimo\",\n \"Image\":\"stringa base64_encode.....\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "foowd_alpha2/api_foowd/app/routes/actions/FApi/ApiUser.php",
    "groupTitle": "User"
  }
] });