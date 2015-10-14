<?php

namespace Uoowd;

require_once(elgg_get_plugins_path().\Uoowd\Param::uid().'/vendor/autoload.php');

// lato elgg vedere
// https://elgg.org/discussion/view/1472571/add-sendmail-parameters



class FoowdMailer extends \PHPMailer {


	public function __construct(){

		//Enable SMTP debugging. 
		// $this->SMTPDebug = 3;                               
		//Set PHPthiser to use SMTP.
		$this->isSMTP();            
		//Set SMTP host name                          
		

		// $this->addAddress("nuclear.quantum@gthis.com", "Recepient Name");

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


// $mail = new PHPMailer;

// //Enable SMTP debugging. 
// $mail->SMTPDebug = 3;                               
// //Set PHPMailer to use SMTP.
// $mail->isSMTP();            
// //Set SMTP host name                          
// $mail->Host = "smtp.gmail.com";
// //Set this to true if SMTP host requires authentication to send email
// $mail->SMTPAuth = true;                          
// //Provide username and password     
// $mail->Username = "test.foowd@gmail.com";                 
// $mail->Password = "casellaDiTest";                           
// //If SMTP requires TLS encryption then set it
// $mail->SMTPSecure = "tls";                           
// //Set TCP port to connect to 
// $mail->Port = 587;                                   

// $mail->From = "test.foowd@gmail.com";
// $mail->FromName = "Sito Foowd";

// $mail->addAddress("nuclear.quantum@gmail.com", "Recepient Name");

// $mail->isHTML(true);

// $mail->Subject = "Subject Text";
// $mail->Body = "<i>Mail body in HTML</i>";
// $mail->AltBody = "This is the plain text version of the email content";






// if(!$mail->send()) 
// {
//     \Fprint::r("Mailer Error: " . $mail->ErrorInfo);
// } 
// else 
// {
//     \Fprint::r( "Message has been sent successfully");
// }
