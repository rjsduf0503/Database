<?php
require('src/PHPMailer.php');
require('src/SMTP.php');
require('src/Exception.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// mailer("rjsduf0503@naver.com","rjsduf0503@naver.com","rjsduf0503@naver.com","ㅎㅇ","ㅎㅇ");
// $mail;

// Load Composer's autoloader
// require 'vendor/autoload.php';
function mailer($fname, $fmail, $to, $subject, $content, $type=0, $file="", $cc="", $bcc="")
{
    //   if ($type != 1) $content = nl2br($content);
      // type : text=0, html=1, text+html=2
      $mail = new PHPMailer(true); // defaults to using php "mail()"
      $mail->SMTPOptions = array(
          'ssl' => array(
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true
          )
        );
    //   $mail->SMTPDebug = SMTP::DEBUG_SERVER;
      $mail->IsSMTP();
      $mail->Host = "smtp.naver.com";
      $mail->SMTPAuth = true;
      $mail->Username = "rjsduf0503";
      $mail->Password = "chess00700";
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port = 465;
      
      $mail->CharSet = 'UTF-8';
      $mail->setFrom($fmail, $fname);
      $mail->addAddress($to);
      $mail->Subject = $subject;
      $mail->AltBody = ""; // optional, comment out and test
      $mail->msgHTML($content);
      if ($cc)
            $mail->addCC($cc);
      if ($bcc)
            $mail->addBCC($bcc);
      if ($file != "") {
            foreach ($file as $f) {
                  $mail->addAttachment($f['path'], $f['name']);
            }
      }
      $mail->send();
    //   if ( $mail->send() ) echo "성공";
    //   else echo "실패";
}

?>