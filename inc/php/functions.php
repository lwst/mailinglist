<?php

function format_email($info, $format){

	$root = $_SERVER['DOCUMENT_ROOT'].'/php/mailinglist';

	$template = file_get_contents($root.'/signup_template.'.$format);
			
	$template = preg_replace('{EMAIL}', $info['email'], $template);
	$template = preg_replace('{KEY}', $info['key'], $template);
	$template = preg_replace('{SITEPATH}','http://cravatteloic.be', $template);
		
	return $template;

}

function send_email($info){
		
	$body = format_email($info,'html');
	$body_plain_txt = format_email($info,'txt');

	$transport = Swift_MailTransport::newInstance();
	$mailer = Swift_Mailer::newInstance($transport);
	$message = Swift_Message::newInstance();
	$message ->setSubject('Please validate your email');
	$message ->setFrom(array('cravatteloic@gmail.com.com' => 'Cravatte Loïc'));
	$message ->setTo(array($info['email'] => $info['username']));
	
	$message ->setBody($body_plain_txt);
	$message ->addPart($body, 'text/html');
			
	$result = $mailer->send($message);
	
	return $result;
	
}

function show_errors($action){

	$error = false;

	if(!empty($action['result'])){
	
		$error = "<ul class=\"alert $action[result]\">"."\n";

		if(is_array($action['text'])){
	
			foreach($action['text'] as $text){
			
				$error .= "<li><p>$text</p></li>"."\n";
			
			}	
		
		}else{
		
			$error .= "<li><p>$action[text]</p></li>";
		
		}
		
		$error .= "</ul>"."\n";
		
	}

	return $error;

}