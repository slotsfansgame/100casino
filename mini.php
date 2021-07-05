<?php

error_reporting(0);

$cache = TRUE;

// Папка для хранения кэша
$cachedir = dirname(__FILE__) . '/assets/cache';
$cssdir   = dirname(__FILE__) . '/css';
$jsdir    = dirname(__FILE__) . '/js';

//Если файлы не переданы выводим 404 ошибку
if (!isset($_GET['files']) or strlen($_GET['files']) == 0)
{
	header('Status: 404 Not Found');
	exit();
}

if (!file_exists($cachedir))
{
	mkdir($cachedir);
}

$type = $_GET['type'];

switch ($type) {
	case 'css':
		$base = realpath($cssdir);
		break;
	case 'javascript':
		$base = realpath($jsdir);
		break;
	default:
		header ("HTTP/1.0 503 Not Implemented");
		exit;
};


//Получаем массив файлов
$files = explode(',', $_GET['files']);
$types = array_fill(0,sizeof($files),$type);

/**
 * Функция добавляет разширение файла в зависимости от типа
 */
function addExtension($file,$type)
{
	if ($type == 'javascript' && substr($file, -3) !== '.js'){		
		$file .= '.js';
	} 
	elseif($type == 'css' && substr($path, -4) !== '.css') {
		$file .= '.css';
	}

	return $file;
}

// Добавляем разширение для переданных файлов
$files = array_map('addExtension',$files,$types);

$lastmodified = 0;

// Проверяем существуют ли переданные файлы
// и получаем время последнего редактирования
foreach ($files as $file){
	$path = realpath($base.'/'.$file);

	if (!file_exists($path)) {
		header ("HTTP/1.0 404 Not Found");
		exit;
	}
	
	$lastmodified = max($lastmodified, filemtime($path));	
}

//Создаем хеш файлов
$md5 = md5($_GET['files']);
$hash = $lastmodified . '-'.$md5;

header ("Etag: \"" . $hash . "\"");

// Если есть файлы в кэше браузера, то ничего не выводим
if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"' . $hash . '"') 
{
	header ("HTTP/1.0 304 Not Modified");
	header ('Content-Length: 0');
	exit;
} 
else 
{
	
	// Determine supported compression method
	$gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
	$deflate = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate');

	// Determine used compression method
	$encoding = $gzip ? 'gzip' : ($deflate ? 'deflate' : 'none');

	// Check for buggy versions of Internet Explorer
	if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Opera') && 
		preg_match('/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches)) {
		$version = floatval($matches[1]);
		
		if ($version < 6)
			$encoding = 'none';
			
		if ($version == 6 && !strstr($_SERVER['HTTP_USER_AGENT'], 'EV1')) 
			$encoding = 'none';
	}
	
	
	if($cache){				
						
		// Try the cache first to see if the combined files were already generated
		$cachefile = 'cache-' .$md5. '.' .(($type === 'css') ? 'css': 'js') . (($encoding != 'none')? '.' . $encoding : '');		
		
		if (file_exists($cachedir.'/'.$cachefile) && $lastmodified < filemtime($cachedir.'/'.$cachefile)) {
			
			if ($encoding != 'none') {
				header ("Content-Encoding: " . $encoding);
			}
					
			header ("Content-Type: text/".$type);
			header ("Content-Length: " . filesize($cachedir.'/'.$cachefile));
	
			readfile($cachedir.'/'.$cachefile);
			exit;
		}
	}
	
	// Get contents of the files
	$contents = '';
	
	foreach ($files as $file){
		$path = realpath($base.'/'.$file);
		$contents .= file_get_contents($path);
	}
	
	
	if($type === 'css'){
		// Remove comments
		$contents = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $contents);	
		// Remove tabs, spaces, newlines, etc...
		$contents = str_replace(array("\r", "\n", "\t", '  ', '   '), '', $contents);
	}
	else{
		require('jsmin.php');
		$contents = JSMin::minify($contents);
	}

	// Send Content-Type
	header ("Content-Type: text/".$type);
	
	if (isset($encoding) && $encoding != 'none') 
	{
		// Send compressed contents
		$contents = gzencode($contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);
		header ("Content-Encoding: " . $encoding);
		header ('Content-Length: ' . strlen($contents));
		echo $contents;
	} 
	else 
	{
		// Send regular contents
		header ('Content-Length: ' . strlen($contents));
		echo $contents;
	}

	
	if($cache) file_put_contents($cachedir.'/'.$cachefile, $contents);	
}