<?php

namespace Uoowd;

class MessageEmail{


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
	 * messaggio mail che giunge a chi chiude l'ordine e si prende carico di tutto
	 * @param  [type] $ar [description]
	 * @return [type]     [description]
	 */
	public function managerOrderMsg($ar){
		// $ar = array();
		// $ar['mngrUsr'] = 'random';
		// $ar['ofName'] = 'gran bella roba';
		// $ar['pubEmail'] = 'via@rnd.com';
		// $ar['ofId'] = 2;
		// $ar['detailsRowAlt'] = $row->altMsg;
		// $ar['detailsRowHtml'] = $row->htmlMsg;

		extract($ar);

		$managerMsgAlt = '
		Salve %s, 

		l\'offerta "%s" e\' stata presa in carico con successo. 

		Di seguito riepiloghiamo i dettagli:

		%s

		Per completare le procedure di pagamento e riscossione deve contattare il promotore dell\'offerta all\'indirizzo

		   %s

		Cordialmente,
		Foowd
		';
		$managerMsgAlt = preg_replace("@^( {4}||\t{2})@m", '', $managerMsgAlt);
		unset($ar);

		$alt = array( $mngrUsr, $ofName, $detailsRowAlt, $pubEmail );

		$ofUrl = '<a href="'.\Uoowd\Param::offerUrl($ofId).'">'.$ofName.'</a>';

		$managerMsgHtml = '
		Salve <b>%s</b>,<br/> 

		<p>l\'offerta <b>%s</b> e\' stata presa in carico con successo.</p> <br/>

		Di seguito riepiloghiamo i dettagli:

			<table style="background-color: rgb(234, 228, 209); margin: 1em; border-spacing: 0.7em;">
			<tbody>
			%s
			</tbody>
			</table>

		Per completare le procedure di pagamento e riscossione deve contattare il promotore dell\'offerta all\'indirizzo<br/>

		   <p style="margin-left: 1em; font-weight: bold;">%s</p>

		Cordialmente,<br/>
		Foowd
		';

		$html = array( $mngrUsr, $ofUrl, $detailsRowHtml, $pubEmail );

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
		// $ar['mngrUsr'] = 'random';
		// $ar['mngrMail'] = 'via@lemani.com';
		// $ar['ofName'] = 'gran bella roba';
		// $ar['ofId'] = '2';
		// $ar['qt'] = 20;
		// $ar['price'] = 300.25;
		// trasformo le chiavi in variabili... comodo!
		extract($ar);
		// prime tot e poi price!
		$tot = number_format($qt*$price, 2, ',', ' ');
		$price = number_format($price, 2, ',', ' ');

		$publisherMsgAlt ='
		Salve %s,

		l\'utente %s ha deciso di prendere in carico l\'ordinazione relativa all\'offerta 

		    %s

		secondo quanto specificato:

		Quote totali     :  %13s
		Prezzo per quota :  %13s euro Cad.
		--------------------------------
		Totale:            %13s euro


		Per maggiori dettagli deve contattare %s all\'indirizzo %s .

		Cordialmente,
		Foowd
		';
		$publisherMsgAlt = preg_replace("@^( {4}||\t{2})@m", '', $publisherMsgAlt);
		unset($ar);
		$alt = array($pubUsr, $mngrUsr, $ofName, $qt, $price, $qt*$price, $mngrUsr, $mngrMail);

		$ofUrl = '<a href="'.\Uoowd\Param::offerUrl($ofId).'">'.$ofName.'</a>';

		$publisherMsgHtml = $this->styles . '
		Salve <b>%s</b>,
		<br/>
		l\'utente <b>%s</b> ha deciso di prendere in carico l\'ordinazione relativa all\'offerta 
		    <p style="margin-left: 1em;">%s</p>
		secondo quanto specificato:

		<table style="background-color: rgb(234, 228, 209); margin: 1em; border-spacing: 0.7em;"><tbody>
			<tr>
			<td>Quote totali:</td><td style="text-align:right;">%s</td><td>X</td>
			</tr>
			<tr>
			<td>Prezzo per quota:</td><td style="text-align:right;">%s</td><td>&euro; Cad.</td>
			</tr>
			<tr style="outline: thin solid;">
			<td>Totale:</td><td style="text-align:right;">%s</td><td>&euro;</td>
			</tr>
			</tbody>
		</table>

		Per maggiori dettagli deve contattare <b>%s</b> all\'indirizzo email
			<p style="margin-left: 1em; font-weight: bold;">%s</p>

		Cordialmente,<br/>
		Foowd
		';
		$html = array($pubUsr, $mngrUsr, $ofUrl, $qt, $price, $tot, $mngrUsr, $mngrMail);	

		$tmp = new \stdClass();
		$tmp->htmlMsg = vsprintf($publisherMsgHtml, $html);
		$tmp->altMsg = vsprintf($publisherMsgAlt, $alt);

		return $tmp;

	}


}