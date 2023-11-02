<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mail = new PHPMailer();
$autoresponder = new PHPMailer();

//$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = '';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = '';                 // SMTP username
$mail->Password = '';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to



if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if( $_POST['form_name'] != '' AND $_POST['form_phone'] != '' AND $_POST['form_email'] != '' AND $_POST['form_subject'] != '' AND $_POST['form_message'] != '' ) {

        $form_name = $_POST['form_name'];
        $form_email = $_POST['form_phone'];
        $form_subject = $_POST['form_email'];
        $form_phone = $_POST['form_subject'];
        $form_message = $_POST['form_message'];
      

		$subject = isset($subject) ? $subject : 'New Message From Contact Form' ;

		$botcheck = $_POST['contact-form-botcheck'];

        $toemail = 'vikhil@loopmea.com'; // Your Email Address
        $toname = 'assuretek'; // Your Name

		if( $botcheck == '' ) {

			$mail->SetFrom( $form_email , $form_name );
			$mail->AddReplyTo( $form_email , $form_name );
			$mail->AddAddress( $toemail , $toname );
			$mail->Subject = $form_subject;
			$autoresponder->SetFrom( $toemail , $toname );
			$autoresponder->AddReplyTo( $toemail , $toname );
			$autoresponder->AddAddress( $form_email , $form_name );
			$autoresponder->Subject = 'We\'ve received your Email';

			$ar_body = "Thank you for contacting us. We will reply within 24 hours.<br><br>Regards,<br>Your Company.";

			$form_name = isset($form_name) ?  "<br><br> Name:$form_name<br><br>" : '';
			$form_email = isset($form_email) ? "Email: $form_email<br><br>" : '';
			$form_phone = isset($form_phone) ? "Phone: $form_phone<br><br>" : '';
				$form_subject = isset($form_subject) ? "Phone: $form_subject<br><br>" : '';
			$form_message = isset($form_message) ? "Message: $form_message<br><br>" : '';

			$referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>This Form was submitted from: ' . $_SERVER['HTTP_REFERER'] : '';

			$body =  "$subject $form_name  $form_email $form_phone $form_subject  $form_message $referrer";

			$ar_body = "Thank you for contacting us. We will reply within 24 hours.<br><br>Regards,<br>Your Company.";

			$autoresponder->MsgHTML( $ar_body );
			$mail->MsgHTML( $body );
			$sendEmail = $mail->Send();

			if( $sendEmail == true ):
				$send_arEmail = $autoresponder->Send();
				echo 'We have <strong>successfully</strong> received your Message and will get Back to you as soon as possible.';
			else:
				echo 'Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.<br /><br /><strong>Reason:</strong><br />' . $mail->ErrorInfo . '';
			endif;
		} else {
			echo 'Bot <strong>Detected</strong>.! Clean yourself Botster.!';
		}
	} else {
		echo 'Please <strong>Fill up</strong> all the Fields and Try Again.';
	}
} else {
	echo 'An <strong>unexpected error</strong> occured. Please Try Again later.';
}

?>