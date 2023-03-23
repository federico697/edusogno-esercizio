<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$mailer = new PHPMailer(true); // creo un nuovo oggetto di tipo PHPMailer
$mailer->isSMTP(); // imposto il protocollo di trasmissione come SMTP
$mailer->Host = 'smtp.libero.it'; //  imposto l'host del server SMTP
$mailer->SMTPAuth = true; // abilito l'autenticazione SMTP
$mailer->SMTPSecure = "ssl"; //imposto il tipo di connessione sicura come SSL.
$mailer->Port = 465; //questa riga imposta la porta del server SMTP.
$mailer->Username = 'edusogno-esercizio@libero.it';
$mailer->Password = 'Edusogno123!';
