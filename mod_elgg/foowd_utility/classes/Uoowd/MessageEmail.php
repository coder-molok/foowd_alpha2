<?php

namespace Uoowd;

class MessageEmail{


	/**
	 * Quando qualcuno si prende in carico l'ordinazione, 
	 * questo messaggio giunge a ciascun utente suo amico 
	 * nel caso di chiusura immediata.
	 * @param  [type] $ar [description]
	 * @return [type]     [description]
	 */
	public function userOrderSingleMsg($ar){
		// $ar = array();
		// $ar['singleUsr'] ='enomis';
		// $ar['mngrUsr'] = 'random';
		// $ar['mngrEmail'] = 'via@rnd.com';
		// $ar['ofName'] = 'gran bella roba';
		// $ar['ofId'] = 2;
		// $ar['qt'] = 22;
		// $ar['price'] = 10.23;

		extract($ar);
		// prima tot e poi price!
		$tot = number_format($qt*$price, 2, ',', ' ');
		$price = number_format($price, 2, ',', ' ');

		$userMsgAlt ='
		Buongiorno %s,

		Qualcuno ha accettato di ricevere il tuo ordine su foowd.it!
		%s si e\' reso disponibile per girare il tuo pagamento al produttore e ricevere la merce.

		Qui troverai il riepilogo per la tua parte dell\'ordine: verificalo e contatta %s su %s per informazioni sul pagamento ed eventuali modifiche.

		Siamo a tua disposizione per dubbi, problemi o feedback.

		Saluti da foowd, e buoni acquisti!


		Segue il riepilogo della tua parte di ordine:

		prodotto:          %s
		preferenze: %13s 
		a:          %13s &euro; Cad.
		-------------------------
		Totale:     %13s &euro;
		';
		$userMsgAlt = preg_replace("@^( {4}||\t{2})@m", '', $userMsgAlt);
		unset($ar);
		$alt = array($singleUsr, $mngrUsr, $mngrUsr, $mngrEmail, $ofName, $qt, $price, $tot);

		$ofUrl = '<a href="'.\Uoowd\Param::offerUrl($ofId).'">'.$ofName.'</a>';
		$userMsgHtml ='
		<p>Buongiorno <b>%s</b>,</p>

		<p>Qualcuno ha accettato di ricevere il tuo ordine su foowd.it!
		<br/>%s si e\' reso disponibile per girare il tuo pagamento al produttore 
		e ricevere la merce.</p>
		
		<p>Qui troverai il riepilogo per la tua parte dell\'ordine: 
		verificalo e contatta %s su %s per informazioni sul pagamento 
		ed eventuali modifiche.</p>
		
		<p><b>Siamo a tua disposizione per dubbi, problemi o feedback.</b></p>
		<p><em>Saluti da foowd, e buoni acquisti!</em></p>

		<p>Segue il riepilogo della tua parte di ordine:</p>

		<table style="background-color: rgb(234, 228, 209); margin: 1em; border-spacing: 0.7em;"><tbody>
			<tr>
			<td>Prodotto:</td><td></td><td></td><td>%s</td>
			</tr>
			<tr>
			<td>Preferenze:</td><td style="text-align:right;">%s</td><td>X</td>
			</tr>
			<tr>
			<td>Prezzo:</td><td style="text-align:right;">%s</td><td>&euro; Cad.</td>
			</tr>
			<tr style="outline: thin solid;">
			<td>Totale:</td><td style="text-align:right;">%s</td><td>&euro;</td>
			</tr>
			</tbody>
		</table>
		';
		$html = array($singleUsr, $mngrUsr ,$mngrUsr, $mngrEmail, $ofUrl, $qt, $price, $tot);

		$tmp = new \stdClass();
		$tmp->htmlMsg = vsprintf($userMsgHtml, $html);
		$tmp->altMsg = vsprintf($userMsgAlt, $alt);

		return $tmp;
	}
  /**
	 * Quando qualcuno si prende in carico l'ordinazione, 
	 * questo messaggio giunge a ciascun utente suo amico 
	 * che aveva espresso la preferenza, in attesa delle 24h
	 * @param  [type] $ar [description]
	 * @return [type]     [description]
	 */
	public function userOrderFirstMsg($ar){
		// $ar = array();
		// $ar['singleUsr'] ='enomis';
		// $ar['mngrUsr'] = 'random';
		// $ar['mngrEmail'] = 'via@rnd.com';
		// $ar['ofName'] = 'gran bella roba';
		// $ar['ofId'] = 2;
		// $ar['qt'] = 22;
		// $ar['price'] = 10.23;
		// $ar['$timeLimit'] = '18:30';
		// $ar['$dateLimit'] = '15 dicembre (domani)';

		extract($ar);
		// prima tot e poi price!
		$tot = number_format($qt*$price, 2, ',', ' ');
		$price = number_format($price, 2, ',', ' ');

		$userMsgAlt ='
		Buongiorno %s,

		Stai ricevendo questa mail perche\' hai partecipato ad un ordine su foowd.it!
		Il gruppo e\' finalmente al completo e %s si e\' reso disponibile 
		per occuparsi del pagamento e ricevere la merce personalmente.

		Segue il riepilogo del tuo ordine: 
		controlla la quantita\' prenotata e ricorda che hai tempo fino 
		alle %s del %s per correggere la tua quota.

		prodotto:          %s
		preferenze: %13s 
		a:          %13s &euro; Cad.
		-------------------------
		Totale:     %13s &euro;

    Riceverai allo scadere del tempo indicato la conferma dell\'ordine con la quota definitiva.
		Intanto prendi contatto con %s (%s) per coordinarvi con il pagamento.

		Siamo a tua disposizione per dubbi, problemi o feedback.

		Saluti da foowd, e buoni acquisti!
		';
		$userMsgAlt = preg_replace("@^( {4}||\t{2})@m", '', $userMsgAlt);
		unset($ar);
		$alt = array($singleUsr, $mngrUsr, $timeLimit, $dateLimit, $ofName, $qt, $price, $tot, $mngrUsr, $mngrEmail);

		$ofUrl = '<a href="'.\Uoowd\Param::offerUrl($ofId).'">'.$ofName.'</a>';
		$userMsgHtml ='
		<p>Buongiorno <b>%s</b>,</p>

		<p>Stai ricevendo questa mail perche\' hai partecipato ad un ordine su foowd.it!
		<br/>Il gruppo e\' finalmente al completo e <b>%s</b> si e\' reso disponibile 
		per occuparsi del pagamento e ricevere la merce personalmente.</p>

		<p>Segue il riepilogo del tuo ordine: 
		<br/>controlla la quantita\' prenotata e ricorda che hai tempo fino 
		<b>alle %s del %s</b> per correggere la tua quota.</p>

		<table style="background-color: rgb(234, 228, 209); margin: 1em; border-spacing: 0.7em;"><tbody>
			<tr>
			<td>Prodotto:</td><td></td><td></td><td>%s</td>
			</tr>
			<tr>
			<td>Preferenze:</td><td style="text-align:right;">%s</td><td>X</td>
			</tr>
			<tr>
			<td>Prezzo:</td><td style="text-align:right;">%s</td><td>&euro; Cad.</td>
			</tr>
			<tr style="outline: thin solid;">
			<td>Totale:</td><td style="text-align:right;">%s</td><td>&euro;</td>
			</tr>
			</tbody>
		</table>

    <p>Riceverai allo scadere del tempo indicato la conferma dell\'ordine con la quota definitiva.
		<br/>Intanto prendi contatto con <b>%s (%s)</b> per coordinarvi con il pagamento.</p>

		<p>Siamo a tua disposizione per dubbi, problemi o feedback.</p>

		<p><em>Saluti da foowd, e buoni acquisti!</em></p>
		';
		$html = array($singleUsr, $mngrUsr, $timeLimit, $dateLimit, $ofUrl, $qt, $price, $tot ,$mngrUsr, $mngrEmail);

		$tmp = new \stdClass();
		$tmp->htmlMsg = vsprintf($userMsgHtml, $html);
		$tmp->altMsg = vsprintf($userMsgAlt, $alt);

		return $tmp;
	}
	/**
	 * passate le 24h, 
	 * questo messaggio giunge a ciascun utente in attesa 
	 * che aveva espresso la preferenza, la quale ora viene chiusa
	 * @param  [type] $ar [description]
	 * @return [type]     [description]
	 */
	public function userOrderLastMsg($ar){
		// $ar = array();
		// $ar['singleUsr'] ='enomis';
		// $ar['mngrUsr'] = 'random';
		// $ar['mngrEmail'] = 'via@rnd.com';
		// $ar['ofName'] = 'gran bella roba';
		// $ar['ofId'] = 2;
		// $ar['qt'] = 22;
		// $ar['price'] = 10.23;

		extract($ar);
		// prima tot e poi price!
		$tot = number_format($qt*$price, 2, ',', ' ');
		$price = number_format($price, 2, ',', ' ');

		$userMsgAlt ='
		Buongiorno %s

		Stai ricevendo questa mail da foowd.it perche\' un ordine a cui stai partecipando e\' in chiusura!
		E\' esaurito il tempo disponibile per fare correzioni alla tua prenotazione, le quantita\' sotto riportate sono quelle definitive,
		ora e\' sufficiente completare il pagamento tramite il tuo referente  %s  per ricevere il prodotto, che dovrai ritirare presso di lui.

		Segue il riepilogo del tuo ordine: controlla la tua quota e il totale dovuto.

		prodotto:          %s
		preferenze: %13s 
		a:          %13s &euro; Cad.
		-------------------------
		Totale:     %13s &euro;

		Le preferenze indicate sono state gia\' azzerate su foowd.it, puoi fin da subito esprimerne di nuove.

		Riceverai a breve la conferma del produttore per la disponibilita\' dei prodotti e gli estremi di pagamento,
		MA RICORDATI che a pagare deve essere uno solo del gruppo (coordinati col tuo referente %s all\'indirizzo %s ).

		Siamo a tua disposizione per dubbi, problemi o feedback.

		Saluti da foowd, e buoni acquisti!
		';
		$userMsgAlt = preg_replace("@^( {4}||\t{2})@m", '', $userMsgAlt);
		unset($ar);
		$alt = array($singleUsr, $mngrUsr, $ofName, $qt, $price, $tot, $mngrUsr, $mngrEmail);

		$ofUrl = '<a href="'.\Uoowd\Param::offerUrl($ofId).'">'.$ofName.'</a>';
		$userMsgHtml ='
		<p>Buongiorno <b>%s</b>,</p>

		<p>Stai ricevendo questa mail da foowd.it perche\' un ordine a cui stai partecipando e\' in chiusura!
		<br/>E\' esaurito il tempo disponibile per fare correzioni alla tua prenotazione, le quantita\' sotto riportate sono quelle definitive,
		<br/>ora e\' sufficiente completare il pagamento tramite il tuo referente <b>%s</b> per ricevere il prodotto, che dovrai ritirare presso di lui.</p>

		<p>Segue il riepilogo del tuo ordine: 
		<br/>controlla la tua quota e il totale dovuto.</p>

		<table style="background-color: rgb(234, 228, 209); margin: 1em; border-spacing: 0.7em;"><tbody>
			<tr>
			<td>Prodotto:</td><td></td><td></td><td>%s</td>
			</tr>
			<tr>
			<td>Preferenze:</td><td style="text-align:right;">%s</td><td>X</td>
			</tr>
			<tr>
			<td>Prezzo:</td><td style="text-align:right;">%s</td><td>&euro; Cad.</td>
			</tr>
			<tr style="outline: thin solid;">
			<td>Totale:</td><td style="text-align:right;">%s</td><td>&euro;</td>
			</tr>
			</tbody>
		</table>
		
		<strong style="color: #E9106B;">Le preferenze indicate sono state gia\' azzerate su foowd.it, puoi fin da subito esprimerne di nuove.</strong>

    <p>Riceverai a breve la conferma del produttore per la disponibilita\' dei prodotti e gli estremi di pagamento,
		<br/><strong>ma ricordati</strong> che a pagare deve essere uno solo del gruppo (<b>coordinati col tuo referente %s all\'indirizzo %s </b>).</p>

		<p>Siamo a tua disposizione per dubbi, problemi o feedback.</p>

		<p><em>Saluti da foowd, e buoni acquisti!</em></p>
		';
		$html = array($singleUsr, $mngrUsr, $ofUrl, $qt, $price, $tot ,$mngrUsr, $mngrEmail);

		$tmp = new \stdClass();
		$tmp->htmlMsg = vsprintf($userMsgHtml, $html);
		$tmp->altMsg = vsprintf($userMsgAlt, $alt);

		return $tmp;
	}

	/**
	 * messaggio mail che giunge a chi chiude l'ordine
	 * in caso di chiusura immediata dell'ordine.
	 * @param  [type] $ar [description]
	 * @return [type]     [description]
	 */
	public function managerOrderSingleMsg($ar){
		// $ar = array();
		// $ar['mngrUsr'] = 'random';
		// $ar['ofName'] = 'gran bella roba';
		// $ar['pubName'] = 'Azienza Agricola Rnd';
		// $ar['pubEmail'] = 'via@rnd.com';
		// $ar['totqt'] = 100;
		// $ar['ofId'] = 2;
		// $ar['ofDetail'] = array -> v
		// $v['qt'] = 22;
		// $v['price'] = 10.23;
		// $v['singleUsr'] = 'partecipante'

		extract($ar);

		$price = (count($ofDetail)>0?$ofDetail[0]['price']:0)
		$ttot = number_format($tqt*$price, 2, ',', ' ');
		
		$dettaglio = array_map("managerSingleOrderMsg", $ofDetail);

		$managerMsgAlt = '
		Buongiorno %s,
		
		Grazie per aver chiuso un ordine coi tuoi amici!
		
		Gli altri partecipanti stanno ricevendo istruzioni per contattarti e girarti il pagamento (puoi scegliere la modalita\' che ti e\' piu\' comoda: al produttore dovrai pagare direttamente il totale).

		Una volta completati pagamenti o eventuali modifiche ti e\' sufficiente copia/incollare l\'elenco di riepilogo riportato piu\' avanti e inviarlo a %s all\'indirizzo %s (usa come oggetto "Ordine da foowd" per garantirti una risposta piu\' rapida).

		Sara\' lui/lei a indicarti gli estremi per il pagamento e tutte le informazioni (e le tempistiche) utili alla ricezione dell\'ordine.

		Siamo a tua disposizione per dubbi, problemi o feedback.

		Saluti da foowd, e buoni acquisti!


		Segue il riepilogo complessivo:
		prodotto:   %s

		%s
		
		in totale        %5s 

		-------------------------
		Totale:     %13s &euro;

		';
		$managerMsgAlt = preg_replace("@^( {4}||\t{2})@m", '', $managerMsgAlt);
		unset($ar);
		
		$det = "";
		foreach ($dettaglio as $d) $det.=$d->altMsg;

		$alt = array( $mngrUsr, $pubName, $pubEmail, $ofName, $det, $totqt, $ttot);

		$ofUrl = '<a href="'.\Uoowd\Param::offerUrl($ofId).'">'.$ofName.'</a>';

		$managerMsgHtml = '
		<p>Buongiorno <b>%s</b>,</p>

		<p>Grazie per aver chiuso un ordine coi tuoi amici!</p>
		<p>Gli altri partecipanti stanno ricevendo istruzioni per contattarti e girarti il pagamento (puoi scegliere la modalita\' che ti e\' piu\' comoda: al produttore dovrai pagare direttamente il totale).</p>
		<p>Una volta completati pagamenti o eventuali modifiche ti e\' sufficiente copia/incollare l\'elenco di riepilogo riportato piu\' avanti e inviarlo a <b>%s</b> all\'indirizzo <b>%s</b> (usa come <b>oggetto "Ordine da foowd"</b> per garantirti una risposta piu\' rapida).</p>
		<p>Sara\' lui/lei a indicarti gli estremi per il pagamento e tutte le informazioni (e le tempistiche) utili alla ricezione dell\'ordine.</p>
		
		<p><b>Siamo a tua disposizione per dubbi, problemi o feedback.</b></p>
		<p><em>Saluti da foowd, e buoni acquisti!</em></p>

		<p>Segue il riepilogo complessivo:

		<table style="background-color: rgb(234, 228, 209); margin: 1em; border-spacing: 0.7em;"><tbody>
			<tr>
			<td>Prodotto:</td><td></td><td></td><td>%s</td>
			</tr>
			%s
			<tr>
			<td>in totale:</td><td style="text-align:right;">%s</td><td></td>
			</tr>
			<tr style="outline: thin solid;">
			<td>Totale:</td><td style="text-align:right;">%s</td><td>&euro;</td>
			</tr>
			</tbody>
		</table>
		</p>
		';

		$det = "";
		foreach ($dettaglio as $d) $det.=$d->htmlMsg;

		$html = array( $mngrUsr, $pubName, $pubEmail, $ofUrl, $det, $totqt, $ttot);

		$tmp = new \stdClass();
		$tmp->htmlMsg = vsprintf($managerMsgHtml, $html);
		$tmp->altMsg = vsprintf($managerMsgAlt, $alt);

		return $tmp;
	}
	/**
	 * messaggio mail che giunge a chi chiude l'ordine a 24h dalla chiusura effettiva.
	 * @param  [type] $ar [description]
	 * @return [type]     [description]
	 */
	public function managerOrderFirstMsg($ar){
		// $ar = array();
		// $ar['mngrUsr'] = 'random';
		// $ar['ofName'] = 'gran bella roba';
		// $ar['pubName'] = 'Azienza Agricola Rnd';
		// $ar['pubEmail'] = 'via@rnd.com';
		// $ar['ofId'] = 2;
		// $ar['qt'] = 22;
		// $ar['price'] = 10.23;
		// $ar['tqt'] = 100;
		// $ar['$timeLimit'] = '18:30';
		// $ar['$dateLimit'] = '15 dicembre (domani)';


		extract($ar);
		// prima tot e poi price!
		$tot = number_format($qt*$price, 2, ',', ' ');
		$ttot = number_format($tqt*$price, 2, ',', ' ');
		$price = number_format($price, 2, ',', ' ');

		$managerMsgAlt = '
		Buongiorno %s,

		Stai ricevendo questa mail perche\' hai completato un ordine su foowd.it!
		Congratulazioni, e grazie per la tua disponibilita\'!
		Tutti gli altri partecipanti al gruppo stanno ricevendo un messaggio dove sei nominato come riferimento per questo ordine, per qualunque necessita\' quindi non esitare a contattarci.

		Qui segue il riepilogo del tuo ordine:

		prodotto:          %s

		preferenze: %13s 
		a:          %13s &euro; Cad.
		-------------------------
		Totale:     %13s &euro;

		Ti ricordiamo che hai tempo fino alle %s del %s per correggere la tua quota.
		Passato lo scadere di queste 24 ore, l\'ordine definitivo sara\' inviato al produttore!

		Segue il riepilogo temporaneo delle prenotazioni attuali, compresa anche la tua quota. Controlla il totale dovuto al produttore.

		preferenze: %13s 
		a:          %13s &euro; Cad.
		-------------------------
		Totale:     %13s &euro;

		Ti consigliamo di iniziare a contattare gli altri partecipanti per coordinarvi con il saldo delle loro quote, anche se, per il momento, possono ancora fare delle variazioni.


		Siamo a tua disposizione per dubbi, problemi o feedback.

		Saluti da foowd, e buoni acquisti!
		';
		$managerMsgAlt = preg_replace("@^( {4}||\t{2})@m", '', $managerMsgAlt);
		unset($ar);

		$alt = array( $mngrUsr, $ofName, $qt, $price, $tot, $timeLimit, $dateLimit, $tqt, $price, $ttot);

		$ofUrl = '<a href="'.\Uoowd\Param::offerUrl($ofId).'">'.$ofName.'</a>';

		$managerMsgHtml = '
		<p>Buongiorno <b>%s</b>,</p>

		<p>Stai ricevendo questa mail perche\' hai completato un ordine su foowd.it!
		<br>Congratulazioni, e grazie per la tua disponibilita\'!
		<br>Tutti gli altri partecipanti al gruppo stanno ricevendo un messaggio dove <b>sei nominato come riferimento</b> per questo ordine, per qualunque necessita\' quindi non esitare a contattarci.</p>

		<p>Qui segue il riepilogo del tuo ordine:

		<table style="background-color: rgb(234, 228, 209); margin: 1em; border-spacing: 0.7em;"><tbody>
			<tr>
			<td>Prodotto:</td><td></td><td></td><td>%s</td>
			</tr>
			<tr>
			<td>Preferenze:</td><td style="text-align:right;">%s</td><td>X</td>
			</tr>
			<tr>
			<td>Prezzo:</td><td style="text-align:right;">%s</td><td>&euro; Cad.</td>
			</tr>
			<tr style="outline: thin solid;">
			<td>Totale:</td><td style="text-align:right;">%s</td><td>&euro;</td>
			</tr>
			</tbody>
		</table>
		</p>
		<p>Ti ricordiamo che hai tempo fino <b>alle %s del %s</b> per correggere la tua quota.
		<br/>Passato lo scadere di queste 24 ore, l\'ordine definitivo sara\' inviato al produttore!</p>

		<p>Segue il riepilogo temporaneo delle prenotazioni attuali, compresa anche la tua quota. Controlla il totale dovuto al produttore.
		<table style="background-color: rgb(234, 228, 209); margin: 1em; border-spacing: 0.7em;"><tbody>
			<tr>
			<td>Preferenze:</td><td style="text-align:right;">%s</td><td>X</td>
			</tr>
			<tr>
			<td>Prezzo:</td><td style="text-align:right;">%s</td><td>&euro; Cad.</td>
			</tr>
			<tr style="outline: thin solid;">
			<td>Totale:</td><td style="text-align:right;">%s</td><td>&euro;</td>
			</tr>
			</tbody>
		</table>
		</p>

		<p>Ti consigliamo di iniziare a contattare gli altri partecipanti per coordinarvi con il saldo delle loro quote, anche se, <strong>per il momento, possono ancora fare delle variazioni</strong>.</p>

		<p>Siamo a tua disposizione per dubbi, problemi o feedback.</p>

		<p><em>Saluti da foowd, e buoni acquisti!</em></p>
		';

		$html = array( $mngrUsr, $ofUrl, $qt, $price, $tot, $timeLimit, $dateLimit, $tqt, $price, $ttot);

		$tmp = new \stdClass();
		$tmp->htmlMsg = vsprintf($managerMsgHtml, $html);
		$tmp->altMsg = vsprintf($managerMsgAlt, $alt);

		return $tmp;
	}

	/**
	 * messaggio mail che giunge al momento dell'ordine
	 * a chi ha chiuso l'ordine e si prende carico di tutto.
	 * @param  [type] $ar [description]
	 * @return [type]     [description]
	 */
	public function managerOrderLastMsg($ar){
		// $ar = array();
		// $ar['mngrUsr'] = 'random';
		// $ar['ofName'] = 'gran bella roba';
		// $ar['pubEmail'] = 'via@rnd.com';
		// $ar['ofId'] = 2;
		// $ar['price'] = 10.23;
		// $ar['tqt'] = 100;
		// $ar['$timeLimit'] = '18:30';
		// $ar['$dateLimit'] = '15 dicembre (domani)';


		extract($ar);
		// prima tot e poi price!
		$tot = number_format($qt*$price, 2, ',', ' ');
		$ttot = number_format($tqt*$price, 2, ',', ' ');
		$price = number_format($price, 2, ',', ' ');

		$managerMsgAlt = '
		Buongiorno %s

		Stai ricevendo questa mail perche\' hai completato un ordine su foowd.it!
		Si e\' esaurito il tempo per fare correzioni! Tutti gli altri partecipanti al gruppo stanno ricevendo un riepilogo del loro ordine, e a breve riceverete dal produttore gli estremi per il pagamento.
		Ti ricordiamo che, una volta ricevuto l\'ordine gia\' partizionato, gli altri partecipanti passeranno a ritirarlo presso di te.

		Segue il riepilogo completo dell\'ordine, compresa anche la tua quota. Controlla il totale dovuto al produttore.

		prodotto:          %s
		preferenze: %13s 
		a:          %13s &euro; Cad.
		-------------------------
		Totale:     %13s &euro;
		
		Contatta il produttore all\'indirizzo %s

		Siamo a tua disposizione per dubbi, problemi o feedback.

		Saluti da foowd, e buoni acquisti!
		';
		$managerMsgAlt = preg_replace("@^( {4}||\t{2})@m", '', $managerMsgAlt);
		unset($ar);

		$alt = array( $mngrUsr, $ofName, $qt, $price, $tot, $pubEmail);

		$ofUrl = '<a href="'.\Uoowd\Param::offerUrl($ofId).'">'.$ofName.'</a>';

		$managerMsgHtml = '
		<p>Buongiorno <b>%s</b>,</p>

		<p>Stai ricevendo questa mail perche\' hai completato un ordine su foowd.it!
		<br>Si e\' esaurito il tempo per fare correzioni! Tutti gli altri partecipanti al gruppo stanno ricevendo un riepilogo del loro ordine, e a breve riceverete dal produttore gli estremi per il pagamento.
		<br>Ti ricordiamo che, una volta ricevuto l\'ordine gia\' partizionato, gli altri partecipanti passeranno a ritirarlo <b>presso di te</b>.</p>

		<p>Segue il riepilogo completo dell\'ordine, compresa anche la tua quota. Controlla il totale dovuto al produttore.
		<table style="background-color: rgb(234, 228, 209); margin: 1em; border-spacing: 0.7em;"><tbody>
			<tr>
			<td>Prodotto:</td><td></td><td></td><td>%s</td>
			</tr>
			<tr>
			<td>Preferenze:</td><td style="text-align:right;">%s</td><td>X</td>
			</tr>
			<tr>
			<td>Prezzo:</td><td style="text-align:right;">%s</td><td>&euro; Cad.</td>
			</tr>
			<tr style="outline: thin solid;">
			<td>Totale:</td><td style="text-align:right;">%s</td><td>&euro;</td>
			</tr>
			</tbody>
		</table>
		</p>

		<p>Contatta il produttore all\'indirizzo <b><a href="mailto:%s">%s</a></b></p>
		<p>Siamo a tua disposizione per dubbi, problemi o feedback.</p>
		<p><em>Saluti da foowd, e buoni acquisti!</em></p>
		';

		$html = array( $mngrUsr, $ofUrl,  $qt, $price, $tot, $pubEmail );

		$tmp = new \stdClass();
		$tmp->htmlMsg = vsprintf($managerMsgHtml, $html);
		$tmp->altMsg = vsprintf($managerMsgAlt, $alt);

		return $tmp;
	}

	/**
	 * i singoli ordini da elencare in managerOrderMsg e che possono qui essere raccolti come stringa
	 * @param  [type] $ar [description]
	 * @return [type]     [description]
	 */
	public function managerSingleOrderMsg($ar){
		// $v = array();
		// $v['qt'] = 12;
		// $v['price'] =  13.37;
		// $v['singleUsr'] = 'singolo';

		extract($ar);
		// prima tot e poi price!
		$tot = number_format($qt*$price, 2, ',', ' ');
		$price = number_format($price, 2, ',', ' ');

		$strAlt = "    - quantita': %5s   X    prezzo(euro/cad): %10s  =  %15s euro  |  utente: %s \n";
		$alt = array($qt, $price, $tot, $singleUsr);

		$strHtml = '
		<tr style="outline: thin solid silver;">
			<td>Quantit&agrave;</td><td style="text-align:right;">%s</td>
			<td>X</td><td>prezzo (&euro;/cad)</td><td style="text-align:right;">%s</td>
			<td>=</td><td style="text-align:right;">%s &euro;</td>
			<td>utente: %s</td>
		</tr>
		';

		$html = array($qt, $price, $tot, $singleUsr);

		$tmp = new \stdClass();
		$tmp->htmlMsg = vsprintf($strHtml, $html);
		$tmp->altMsg = vsprintf($strAlt, $alt);

		return $tmp;

	}


	/**
	 * Messaggio da inviare all'offerente per avvisarlo in merito alla chiusura dell'ordine
	 * @param  [type] $ar [description]
	 * @return [type]     [description]
	 */
	public function publisherOrderMsg($ar){
		// $ar['pubUsr'] = 'coso';
		// $ar['ofName'] = 'gran bella roba';
		// $ar['ofId'] = '2';
		// $ar['qt'] = 20;
		// $ar['price'] = 300.25;
		// $ar['portions'] = array('1'->'1kg','3'->'2kg','5'->'3kg');

		// trasformo le chiavi in variabili... comodo!
		extract($ar);
		// prime tot e poi price!
		$tot = number_format($qt*$price, 2, ',', ' ');
		$price = number_format($price, 2, ',', ' ');

		$publisherMsgAlt ='
		Per maggiori dettagli deve contattare %s all\'indirizzo %s .

		Buongiorno %s,

		Ottime notizie da foowd.it : un gruppo di acquisto ha appena chiuso un ordine dei Tuoi prodotti!

		Segue l\'ordine, con indicate le quantita\' delle diverse porzioni da spedire e i relativi partecipanti all\'acquisto di gruppo:

		Prodotto :    %s
		
		Quote totali     :  %13s
		Prezzo per quota :  %13s euro Cad.
		--------------------------------
		Totale           :  %13s euro

		Porzioni richieste:
		%s

		Conferma la disponibilita\' complessiva dell\'ordine e inserisci modalita\' ed estremi di pagamento nella risposta.
		E\' sufficente cliccare su "Rispondi" perche\' il tuo messaggio arrivi a tutti i partecipanti, ci penseremo noi.

		Saluti da foowd, e buon lavoro!
		';
		$publisherMsgAlt = preg_replace("@^( {4}||\t{2})@m", '', $publisherMsgAlt);
		unset($ar);
		$alt = array($pubUsr, $ofName, $qt, $price, $tot, $portions);

		$ofUrl = '<a href="'.\Uoowd\Param::offerUrl($ofId).'">'.$ofName.'</a>';

		$publisherMsgHtml = '
		<p>Buongiorno <b>%s</b>,</p>

		<p>Ottime notizie da foowd.it : un gruppo di acquisto ha appena chiuso un ordine dei Tuoi prodotti!</p>

		<p>Segue l\'ordine, con indicate le quantita\' delle diverse porzioni da spedire e i relativi partecipanti all\'acquisto di gruppo:
		<table style="background-color: rgb(234, 228, 209); margin: 1em; border-spacing: 0.7em;"><tbody>
			<tr>
			<td>Prodotto:</td><td></td><td></td><td>%s</td>
			</tr>
			<tr>
			<td>Preferenze:</td><td style="text-align:right;">%s</td><td>X</td>
			</tr>
			<tr>
			<td>Prezzo:</td><td style="text-align:right;">%s</td><td>&euro; Cad.</td>
			</tr>
			<tr style="outline: thin solid;">
			<td>Totale:</td><td style="text-align:right;">%s</td><td>&euro;</td>
			</tr>
			</tbody>
		</table>
		</p>
		<p>Porzioni richieste:<br>
		%s
		</p>
		<p>Conferma la disponibilita\' complessiva dell\'ordine e inserisci modalita\' ed estremi di pagamento nella risposta.</p>
		<p>E\' sufficente cliccare su "<code>Rispondi</code>" perche\' il tuo messaggio arrivi a tutti i partecipanti, <strong>ci penseremo noi</strong>.</p>

		<p><em>Saluti da foowd, e buon lavoro!</em></p>
		';
		$html = array($pubUsr, $ofName, $qt, $price, $tot, $portions);	

		$tmp = new \stdClass();
		$tmp->htmlMsg = vsprintf($publisherMsgHtml, $html);
		$tmp->altMsg = vsprintf($publisherMsgAlt, $alt);

		return $tmp;

	}


}