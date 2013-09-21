<?php
if( isset($_POST) ){
	
	//form validation vars
	$formok = true;
	$errors = array();
	
	//sumbission data
	$ipaddress = $_SERVER['REMOTE_ADDR'];
	$date = date('d/m/Y');
	$time = date('H:i:s');
	
	//form data
	$name = $_POST['name'];	
	$email = $_POST['email'];
	$telephone = $_POST['telephone'];
	$enquiry = $_POST['enquiry'];
	$message = $_POST['message'];
	
	//validate form data
	
	//validate name is not empty
	if(empty($name)){
		$formok = false;
		$errors[] = "Niste vnesili vašega imena";
	}
	
	//validate email address is not empty
	if(empty($email)){
		$formok = false;
		$errors[] = "Niste vnesili vašega e-mail naslova";
	//validate email address is valid
	}elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$formok = false;
		$errors[] = "Niste vnesili pravilnega e-mail naslov";
	}
	
	//validate message is not empty
	if(empty($message)){
		$formok = false;
		$errors[] = "Niste vnesili vašega sporoèila";
	}
	//validate message is greater than 20 charcters
	elseif(strlen($message) < 20){
		$formok = false;
		$errors[] = "Vaše sporoèilo mora bit daljše kot 20 znakov";
	}
	
	
	
	//send email if all is ok
	if($formok){
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: www.spletko.eu / Kontaktni obrazec\r\n";
		$headers .= "From: <".$_POST['email'].">" . "\r\n";
		
		$emailbody = "<p>Prejeli ste novo sporoèilo iz spletne strani www.spletko.eu</p>
					  <p><strong>Ime: </strong> {$name} </p>
					  <p><strong>E-mail naslov: </strong> {$email} </p>
					  <p><strong>Telefon: </strong> {$telephone} </p>
					  <p><strong>Zanima me: </strong> {$enquiry} </p>
					  <p><strong>Sporoèilo: </strong> {$message} </p>
					  <p>Sporoèilo je bilo poslano iz IP naslova: {$ipaddress} / dne {$date} / ob {$time}</p>";
		
		mail("info@spletko.eu","Novo sporoèilo - Spletko",$emailbody,$headers);
		
	}
	
	//what we need to return back to our form
	$returndata = array(
		'posted_form_data' => array(
			'name' => $name,
			'email' => $email,
			'telephone' => $telephone,
			'enquiry' => $enquiry,
			'message' => $message
		),
		'form_ok' => $formok,
		'errors' => $errors
	);
		
	
	//if this is not an ajax request
	if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'){
		//set session variables
		session_start();
		$_SESSION['cf_returndata'] = $returndata;
		
		//redirect back to form
		header('location: ' . $_SERVER['HTTP_REFERER']);
	}
}
