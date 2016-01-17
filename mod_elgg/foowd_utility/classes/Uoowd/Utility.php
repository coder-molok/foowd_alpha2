<?php


namespace Uoowd;


class Utility{

	/**
	 * da adesso, prendo tra $between second arrotondati per eccesso ai primi $every secondi;
	 * @param  [type] $between tempo in secondi. esempio scade tra un'ora
	 * @param  [type] $every   tempo in secondi. esempio il crontab controlla ogni 15 minuti. DEVE essere UNIX TIMESTAMP
	 * @param  [type] $baseTime tempo da cui partire.
	 * @return [type]          [description]
	 */
	public static function roundTimeTo($between, $every, $baseTime = null){
		// // Elaboro la data, impostando tra $between ore arrotondate ai primi $round minuti successivi (per via del crontab)
		$now = (is_null($baseTime)) ? new \DateTime() : (new \DateTime())->setTimestamp($baseTime);
		$deltaT = $between;
		$now->add(new \DateInterval('PT'.$deltaT.'S'));
		// secondi dell'orologio
		$s = (int) $now->format('s');
		// minuti dell'orologio
		$m = (int) $now->format('i');		
		// arrotondo ai primi n minuti successivi, ovvero l'orario a cui effettivamente viene eseguito il crontab
		if($every <= 0) goto __skypEvery;
		$round = $every ;
		$seconds = $m * 60 + $s ;
		$nearest = ceil($seconds/$round) * $round;
		$remain = $nearest - $seconds;
		$now->add(new \DateInterval('PT'.$remain.'S'));
		__skypEvery:

		// giorno della settimana, partendo da zero
		$D = (int) $now->format('w');
		// mese dell'anno partendo da zero
		$M = (int) $now->format('m');
		// la data es: 15 Gennaio	
		$ret = array(
			'date' => sprintf("%s %s", $now->format('d'), \Uoowd\FoowdCron::$mesi[$M] ),
			'time' => $now->format('H:i')
		);

		return $ret;

	}


	/**
	 * mando una mail a tutti gli amministratori del sito
	 * @param  array $ar associativo. $ar: 'body' , optional: 'subject'
	 * @return [type]     [description]
	 */
	public static function mailToAdmins($ar){
		$txt = "
		Salve %s , \n
		Ricevi questa mail autogenerata in quanto amministratore del sito %s . \n 

		%s

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
			$subject = (isset($ar['subject'])) ? $ar['subject'] : "Errore in uno script";
			$body = sprintf($txt, $name , elgg_get_site_url(), $ar['body']);
			elgg_send_email($from, $to, $subject, $body, array());
		}
	}

}



// // *********************************************************************************************/
// // Elaboro la data, impostando 24 ore arrotondate ai primi 30 minuti successivi (per via del crontab)
// $now = new DateTime();
// $purch = new \Uoowd\FoowdPurchase();
// $deltaT = $purch->trigger;
// $now->add(new DateInterval('PT'.$deltaT.'S'));
// // giorno della settimana, partendo da zero
// $D = (int) $now->format('w');
// // mese dell'anno partendo da zero
// $M = (int) $now->format('m');
// // secondi dell'orologio
// $s = (int) $now->format('s');
// // minuti dell'orologio
// $m = (int) $now->format('i');

// $dateLimit = sprintf("%s %s (domani)", $now->format('d'), \Uoowd\FoowdCron::$mesi[$M] );
// echo $dateLimit;

// // arrotondo ai primi n minuti successivi, ovvero l'orario a cui effettivamente viene eseguito il crontab
// $round = $purch->cronTab ;
// $seconds = $m * 60 + $s ;
// $nearest = ceil($seconds/$round) * $round;
// $remain = $nearest - $seconds;
// $now->add(new DateInterval('PT'.$remain.'S'));
// $timeLimit = $now->format('H:i');
// echo $timeLimit;
// //********* Fine elaborazione Data ******/