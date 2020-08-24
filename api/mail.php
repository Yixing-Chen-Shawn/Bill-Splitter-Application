<?php

function sendVerifyMail($address, $content)
{
    $template = '<div><p>'.$content.'</p></div>';
    require_once 'phpmailer/class.phpmailer.php';
    require_once 'phpmailer/class.smtp.php';
    $mail = new PHPMailer();
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = 'smtp.mxhichina.com';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->Hostname = 'localhost';
    $mail->CharSet = 'UTF-8';
    $mail->FromName = 'BillSplitter Notice';
    $mail->Username = 'quarter@cstkexie.cn';
    $mail->Password = 'Lin52610***';
    $mail->From = 'quarter@cstkexie.cn';
    $mail->isHTML(true);
    $mail->addAddress($address, 'BillSplitter Notice');
    $mail->Subject = 'BillSplitter Notice';
    $mail->Body = $template;
    $status = $mail->send();

    if ($status) {
        return true;
    } else {
        return false;
    }
}
