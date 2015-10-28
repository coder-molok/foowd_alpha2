<?php

namespace Uoowd;


class FoowdPurchase{


	public function check(){

		$data['type'] = "search";
		$data['State'] = "editable";
		$r = \Uoowd\API::Request('purchase', 'POST', $data);

		if(!$r->response) return;

		// ottengo le ordinazioni in stato editable
		$purcs = $r->body->purchases;

		// ricerca delle ordinazioni da chiudere
		foreach ($purcs as $prc) {
			// utile se voglio esporre un formato di scrittura
			$t = $prc->Created;
			$t = new \DateTime($t);
			$now = new \DateTime();
			$deltaT = $now->diff($t);
			$deltaT->seconds = $now->getTimestamp() - $t->getTimestamp();
			
			// $trigger = 24 * 60 * 60;
			$trigger = 10 * 60 ;

			// non e' ancora passato abbastanza tempo
			if($trigger > $deltaT->seconds) continue;

			$data = array(
				"type" => "solve",
				"PurchaseId" => $prc->Id
			);

			$r = \Uoowd\API::Request('purchase', 'POST', $data);

			if($r->response){
				$this->solve_purchase($data, $r);
			}
			else{
				$this->solve_troublesome($data, $r);
			}

		}

	}

	/**
	 * la chiusura e' andata a buon fine
	 * @param  [type] $data [description]
	 * @param  [type] $r    [description]
	 * @return [type]       [description]
	 */
	public function solve_purchase($data, $r){
		// \Fprint::r(func_get_args());
		// devo fare un riepilogo
		$r = $r->body;

		$prefers = $r->prefers;
		$purchase = $r->purchase;
		$offer = $r->offer;

		$offerName = $offer->Name;
		$offerPrice = $offer->Price;


		// riepilogo produttore
		$leader = get_entity($purchase->LeaderId);
		$msg = 'complimenti leader, il tuo ordine e\' ora concluso' ;
		$emailTo = $leader->email;
		$from = 'Foowd Site';
		$subject = 'Ordine "'.$offerName.'" concluso';
		elgg_send_email($from, $emailTo, $subject, $msg, array(/*'htmlBody'=>$ntf->msg->htmlMsg*/) );

		
		// riepilogo sincoli utenti
		$totalQt = 0;
		foreach($prefers as $p){
			$user = get_entity($p->UserId);

			// raccolgo i total per il produttore
			$totalQt += $p->Qt;

			$msg = 'complimenti %s, il tuo ordine e\' ora concluso
			Riepilogo: offerta %s , acquistate %s a %s cad , per un totale di %s .
			' ;
			$qt = $p->Qt;
			$tot = number_format($qt*$offerPrice, 2, '.', ' ');
			$msg = sprintf($msg, $user->username, $offerName, $qt, $offerPrice , $tot );

			$emailTo = $user->email;
			$from = 'Foowd Site';
			$subject = 'Riepilogo Ordine "'.$offerName.'"';
			// \Fprint::r($msg);
			elgg_send_email($from, $emailTo, $subject, $msg, array(/*'htmlBody'=>$ntf->msg->htmlMsg*/) );

		}


		// riepilogo publisher
		$publisher = get_entity($offer->Publisher);
		
		$msg = 'complimenti %s, l\'ordine e\' ora concluso.
		Riepilogo: offerta %s , acquistate in totale %s quote a %s cad , per un totale di %s .
		' ;
		$qt = $totalQt;
		$tot = number_format($qt*$offerPrice, 2, '.', ' ');
		$msg = sprintf($msg, $publisher->username, $offerName, $qt, $offerPrice , $tot );
		$emailTo = $publisher->email;
		$from = 'Foowd Site';
		$subject = 'Riepilogo Ordine "'.$offerName.'"';
		// \Fprint::r($msg);
		elgg_send_email($from, $emailTo, $subject, $msg, array(/*'htmlBody'=>$ntf->msg->htmlMsg*/) );




	}




	public function solve_troublesome($data, $r){

		$txt = "
		Salve %s , \n
		Ricevi questa mail autogenerata in quanto amministratore del sito %s . \n 
		segnalo un problema di chiusura dell'ordine, in particolare i dati riguardandi sono: \n\n
		RICHIESTA API DB: \n %10s \n
		RISPOSTA API DB: \n %10s \n .

		Si consiglia di controllare il DB.
		";

		// ottengo gli amministratori
		$db_prefix = elgg_get_config('dbprefix');
		$admins = elgg_get_entities(array(
			'type' => 'user',
			'wheres' => "{$db_prefix}users_entity.admin = 'yes'",
			'joins' => "JOIN {$db_prefix}users_entity ON {$db_prefix}users_entity.guid = e.guid"
		));

		// mando una mail a tutti gli amministratori
		foreach( $admins as $adm ){
			$name = $adm->username;
			$from = elgg_get_config('sitename');
			$to = $adm->email;
			$subject = "Errore nel processare un'offerta";
			$body = sprintf($txt, $name , elgg_get_site_url(), json_encode($data), json_encode($r));
			elgg_send_email($from, $to, $subject, $body, array());

		}
	}


}