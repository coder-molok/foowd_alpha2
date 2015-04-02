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
    "filename": "foowd_alpha2/api_offerte/app/routes/actions/FApi/ApiOffer.php",
    "groupTitle": "Offers"
  }
] });