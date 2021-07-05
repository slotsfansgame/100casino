<?
$subject="Привет мир";
$message="Текст письма";
$to = "alena100casino@gmail.com";

If (mail ($to, $subject, $message))
{
Echo "Udachno";
}
Else
{
Echo "ERROR";
}
?>