<?php
// Если запрос не AJAX или не передано действие, выходим
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest' || empty($_REQUEST['action'])) {exit();}

$action = $_REQUEST['action'];

define('MODX_API_MODE', true);
require_once dirname(dirname(__FILE__)).'/index.php';

$modx->getService('error','error.modError');
$modx->getRequest();
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');
$modx->error->message = null;

$output = '';
switch ($action) {
    case 'getContent':
        // Если не передан id страницы, тоже выходим
        $id = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0;
        if (empty($id)) {
            exit();
        };

        $object = $modx->getObject('modResource',$id);
        $output = $object->get('content');
        // Парсим теги MODX
        $maxIterations= (integer) $modx->getOption('parser_max_iterations', null, 10);
        $modx->getParser()->processElementTags('', $output, false, false, '[[', ']]', array(), $maxIterations);
        $modx->getParser()->processElementTags('', $output, true, true, '[[', ']]', array(), $maxIterations);
}


@session_write_close();
exit($output);