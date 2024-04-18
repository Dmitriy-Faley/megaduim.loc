<?php
$name=$_POST['name'];
$phone=$_POST['phone'];


  $output = '<h1>Спасибо! Ваш файл получен.</h1>';

  $to = "volkov99.at@gmail.com"; // адрес почты получателя
  $from = "info@optikadioptrika.ru"; // адрес почты отправителя
  $subject = "Заголовок письма";
  $message = "Содержимое письма";

  $attachment = chunk_split(base64_encode(file_get_contents($_FILES['fileFF']['tmp_name'])));
  $filename = $_FILES['fileFF']['name'];
  $filetype = $_FILES['fileFF']['type'];

  $boundary = md5(date('r', time())); // рандомное число

  $headers = "From: " . $from . "\r\n"; // см. наиболее часто используемые заголовки
  $headers .= "Reply-To: " . $from . "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: multipart/mixed; boundary=\"_1_$boundary\"";

  $message="

--_1_$boundary
Content-Type: multipart/alternative; boundary=\"_2_$boundary\"

--_2_$boundary
Content-Type: text/plain; charset=\"utf-8\"
Content-Transfer-Encoding: 7bit

$message

--_2_$boundary--
--_1_$boundary
Content-Type: \"$filetype\"; name=\"$filename\"
Content-Transfer-Encoding: base64
Content-Disposition: attachment // содержимое является вложенным

$attachment
--_1_$boundary--";



  mail($to, $subject, $message, $headers);

?>