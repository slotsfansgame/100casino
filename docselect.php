<?php
// Формирование данных в формате для обновление документов с родительского сайта
// время обновление документов = текущая дата - 24 часа
// будет искать документы которые старше этого времени
$time = time()-(60*60*24);
// загружаем API Modx
define('MODX_API_MODE', true);
include_once 'manager/includes/config.inc.php';
include_once 'manager/includes/document.parser.class.inc.php';
$modx = new DocumentParser;
$modx->db->connect();
$modx->getSettings();
startCMSSession();
$modx->minParserPasses=2;
// получаем документы для обновления
$res = $modx->db->select("id", $modx->getFullTableName('site_content'), "editedon >= '$time'");
$count = $modx->db->getRecordCount($res);
$data = [];
$data['Chunks'] = [];
$data['resource'] = [];
if ($count) {
	while( $row = $modx->db->getRow( $res ) ) {  
		$tmp1 = [];
		$tmp1['doc'] = $modx->getDocument($row['id']);
		// если нет информации по документу, то не берем его (документ отключен)
		if (!$tmp1['doc']) {
			continue;
		}
		// пнформация по TV полям
		$tmp1['tvdata'] = [];
		$resTV = $modx->db->select("*", $modx->getFullTableName('site_tmplvar_contentvalues'), "contentid = '".$row['id']."'");
		$countTV = $modx->db->getRecordCount($resTV);
		if ($countTV) {
			while( $rowTV = $modx->db->getRow( $resTV ) ) { 
				$tmp1['tvdata'][] = $rowTV;
			}
		}
		$data['resource'][] = $tmp1;
	}
	
}

$res2 = $modx->db->select("*", $modx->getFullTableName('site_htmlsnippets'));
$count = $modx->db->getRecordCount($res2);
$data['Chunks'] = $modx->db->makeArray( $res2);
// выводим json данные
echo json_encode($data);