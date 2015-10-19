<?php

namespace Uoowd;

require_once(elgg_get_plugins_path().\Uoowd\Param::uid().'/vendor/autoload.php');

// lato elgg vedere
// https://elgg.org/discussion/view/1472571/add-sendmail-parameters



class FoowdMailer extends \PHPMailer {



	public function __construct(){

       
        $mailcfgs = array('Host', 'Username','Password', 'From', 'FromName', 'SMTPSecure', 'Port', 'SMTPAuth');
        $pre = 'phpmailer-';
        foreach ($mailcfgs as $val) {
            $v = elgg_get_plugin_setting($pre.$val, \Uoowd\Param::pid());
            // trasformo in booleano
            if($v === 'true') $v = true;
            $this->{$val} = $v;
            // \Fprint::r($this->{$val});
        }

		//Enable SMTP debugging. 
		// $this->SMTPDebug = 3;                               
		//Set PHPthiser to use SMTP.
		$this->isSMTP();            
		//Set SMTP host name                          
		$this->isHTML(true);

	}


    /**
     * Save email to a folder (via IMAP)
     *
     * This function will open an IMAP stream using the email
     * credentials previously specified, and will save the email
     * to a specified folder. Parameter is the folder name (ie, Sent)
     * if nothing was specified it will be saved in the inbox.
     *
     * @author David Tkachuk <http://davidrockin.com/>
     */
    

    public function copyToFolder($folderPath = null) {
        $message = $this->MIMEHeader . $this->MIMEBody;
        $path = "INBOX" . (isset($folderPath) && !is_null($folderPath) ? ".".$folderPath : ""); // Location to save the email
        // \Fprint::r("{" . $this->Host . "}" . $path);
        $imapStream = imap_open("{" . $this->Host . "}" . $path , $this->Username, $this->Password);
        imap_append($imapStream, "{" . $this->Host . "}" . $path, $message);
        imap_close($imapStream);
    }
}


