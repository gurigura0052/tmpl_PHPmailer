<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

mb_language("japanese");
mb_internal_encoding("UTF-8");

$smtp_user = "example@gmail.com"; // gmailのアカウント名
$smtp_password = "passwaord"; // gmailのパスワード

$name = filter_input( INPUT_POST, "name" );
$to = filter_input( INPUT_POST, "mail" );
$tel = filter_input( INPUT_POST, "tel" );
$text = filter_input( INPUT_POST, "text" );
$user_msg = filter_input( INPUT_POST, "userMsg" );
$admin_msg = filter_input( INPUT_POST, "adminMsg" );

$subject = 'veu.jsテストメール';

$user_msg = $user_msg . "\r\n";
$admin_msg = $admin_msg . "\r\n";

$message .= $to . "\r\n";
$message .=  $name . "様\r\n";
$message .= '電話番号：' . $tel . "\r\n";
$message .= '本文：' . "\r\n" . $text . "\r\n";

// メール1通目の設定
$mail = new PHPMailer;
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->CharSet = 'UTF-8';
$mail->SMTPSecure = 'tls';
$mail->Host = "smtp.gmail.com"; // 外部SMTPサーバーのホスト名（gmail）
$mail->Port = 587; // 外部SMTPのポート番号
$mail->IsHTML(false);
$mail->Username = $smtp_user; // gmailのアカウント名
$mail->Password = $smtp_password; // gmailのパスワード

$mail->setFrom('example@gmail.com'); // 送信元アドレス
$mail->FromName = mb_encode_mimeheader(mb_convert_encoding('送信者名',"JIS","UTF-8")); // 送信者名
$mail->addAddress($to); // 送信先アドレス

$mail->Subject = mb_encode_mimeheader(mb_convert_encoding($subject,"JIS","UTF-8")); // 件名

$mail->Body = $user_msg . $message; // 本文

// メールが送信できたか確認
if ($mail->send()) {
    $user_msg = 1;
} else {
    $user_msg = 0;
}

// メール2通目の設定
$mail = new PHPMailer;
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->CharSet = 'UTF-8';
$mail->SMTPSecure = 'tls';
$mail->Host = "smtp.gmail.com"; // 外部SMTPサーバーのホスト名（gmail）
$mail->Port = 587; // 外部SMTPのポート番号
$mail->IsHTML(false);
$mail->Username = $smtp_user; // gmailのアカウント名
$mail->Password = $smtp_password; // gmailのパスワード

$mail->setFrom('example@gmail.com'); // 送信元アドレス
$mail->FromName = mb_encode_mimeheader(mb_convert_encoding('送信者名',"JIS","UTF-8")); // 送信者名
$mail->addAddress('example@gmail.com'); // 送信先アドレス

$mail->Subject = mb_encode_mimeheader(mb_convert_encoding($subject,"JIS","UTF-8")); // 件名

$mail->Body = $admin_msg . $message; // 本文

// メールが送信できたか確認
if ($mail->send()) {
    $admin_msg = 1;
} else {
    $admin_msg = 0;
}

// ユーザー、管理者両方に送信できたか確認
if ($user_msg === 1 && $admin_msg === 1) {
    $status = array('status' => 'success');
} else {
    $status = array('status' => 'error');
}

// $statusをjson形式で返す
echo json_encode($status);