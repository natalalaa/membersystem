<?
setlocale(LC_CTYPE, 'da_DK');

if (!$db_conn = @mysql_connect("localhost", "DATABASE", "PASSWORD"))
{
	echo "Kunne ikke tilsluttes databasen<br>";
	exit;
};
@mysql_select_db("kbhff");


// error_reporting  (E_ERROR | E_WARNING | E_PARSE);
error_reporting  (E_ALL & ~E_NOTICE);

function error_handler ($level, $message, $file, $line, $context) {

if (!($level & error_reporting())) return;

$advarsel = "Error";
if ($level == 1) { $advarsel = "Fejl"; }
if ($level == 2) { $advarsel = "Advarsel"; }
if ($level == 4) { $advarsel = "Syntaksfejl"; }
if ($level == 8) { $advarsel = "Mulig fejl"; }

if ($level == 16) { $advarsel = "CORE_ERROR"; }
if ($level == 32) { $advarsel = "CORE_WARNING"; }
if ($level == 64) { $advarsel = "COMPILE_ERROR"; }
if ($level == 128) { $advarsel = "COMPILE_WARNING"; }
if ($level == 256) { $advarsel = "USER_ERROR"; }
if ($level == 512) { $advarsel = "USER_WARNING"; }
if ($level == 1024) { $advarsel = "USER_NOTICE"; }
if ($level == 2047) { $advarsel = "ERROR"; }

echo <<<_END_

<strong>$advarsel</strong> i $file, linie $line.<br>
Fejlen rapporteres som: $message<br>

_END_;

}

set_error_handler ('error_handler');



function doquery($query)
{
global $db_conn;
    if(!($result = @mysql_query($query, $db_conn)))
    {
		echo "<strong>Error:</strong> ";
		echo mysql_errno($db_conn);
		echo " -  ";
		echo mysql_error($db_conn);
		exit;
    }
    return $result;
}


?>
