<?php
//CKEditor RichText Editor Plugin v3.5.2-alpha
class CKEditor
{
	var $params = array();

	function CKEditor($params)
	{
		$this->params = $params;
	}
	
	function get_cke_settings($params)
	{
		$this->params = $params;
		global $modx, $_lang;
		// language settings
		if(!@include_once($params['cke_path'].'/lang/'. $modx->config['manager_language'] .'.inc.php'))
		    @include_once($params['cke_path'].'/lang/english.inc.php');
	
		$arrToolbar['modx']    = $_lang['cke_lang_toolbar_modx'];
		$arrToolbar['full']    = $_lang['cke_lang_toolbar_full'];
		$arrToolbar['simple']  = $_lang['cke_lang_toolbar_simple'];
		$user_config = file_get_contents($params['cke_path'] . 'user_config.js');
		preg_match_all('@config\.toolbar_([0-9a-zA-Z]+)@',$user_config, $toolbar_name,PREG_SET_ORDER);
		foreach($toolbar_name as $value)
		{
			$arrToolbar[$value['1']] = $value['1'];
		}
	
		foreach ($arrToolbar as $key=>$value)
		{
			$toolbarOptions .= '<option value="'.$key.'"'.($key == $params['toolbarset'] ? ' selected="selected"' : '').'>'.$value."</option>\n";
		}
	
		switch($_SESSION['browser'])
		{
			case 'mz':
			case 'sf':
			case 'op':
				$displayStyle = 'table-row';
				break;
			default:
				$displayStyle = 'block';
		}
	
		$ph = $_lang;
		$ph['display'] = $params['use_editor']==1 ? $displayStyle : 'none';
		$ph['cke_lang_toolbar_title'] = $_lang['cke_lang_toolbar_title'];
		$ph['cke_lang_toolbar_message'] = $_lang['cke_lang_toolbar_message'];
		$ph['toolbarOptions'] = $toolbarOptions;
		
		$gsettings = file_get_contents($params['cke_path'] . 'inc/view_gsettings.inc');
		foreach($ph as $name => $value)
		{
			$name = '[+' . $name . '+]';
			$gsettings = str_replace($name, $value, $gsettings);
		}
		
	return $gsettings;
	}
	
	function get_cke_script($params)
	{
		$this->params = $params;
		$cke_path = MODX_BASE_URL. 'assets/plugins/' . $params['cke_dir'];
		$editor_css_path = ($params['editor_css_path']!=='') ? $params['editor_css_path'] : $cke_path . '/ckeditor/contents.css';
		$width  = (!empty($params['width'])) ? str_replace("px","",$params['width']) : "100%";
		$height = (!empty($params['height'])) ? $params['height'] : "600px";
	
		if($params['frontend']=='false' || ($params['frontend']=='true' && $params['webuser']))
		{
			if($params['use_browser']==1)
			{
				$allowrb = true;
			}
		}
	
		// build ck instances
	    $connector_path = MODX_BASE_URL . 'manager/media/browser/mcpuk/connectors/php/connector.php';
	    $cke_query = '?Connector=' . $connector_path . '&ServerPath=' . MODX_BASE_URL . '&editor=tinymce3&editorpath=' . $cke_path . '/';
	    $mcpuk_path['base']  = MODX_BASE_URL . 'manager/media/browser/mcpuk/browser.php' . $cke_query ;
		$mcpuk_path['image'] = $mcpuk_path['base'] . '&Type=images';
		$mcpuk_path['flash'] = $mcpuk_path['base'] . '&Type=flash';
		$mcpuk_path['link']  = $mcpuk_path['base'] . '&Type=files';
		// $params['format_tags'] ='p;h2;h3;pre';
		if (!empty($params['format_tags'])) $params['format_tags'] = str_replace('/', ';', $params['format_tags']);
		foreach($this->params['elements'] as $ckInstance)
		{
			$ckInstances .= "<script language='javascript' type='text/javascript'>" . PHP_EOL;
			$ckInstances .= "CKEDITOR.replace( '" . $ckInstance . "'," . PHP_EOL;
			$ckInstances .= "{" . PHP_EOL;
			$ckInstances .= "filebrowserBrowseUrl      : '" . $mcpuk_path['base'] . "'," . PHP_EOL;
			$ckInstances .= "filebrowserImageBrowseUrl : '" . $mcpuk_path['image'] . "'," . PHP_EOL;
			$ckInstances .= "filebrowserFlashBrowseUrl : '" . $mcpuk_path['flash'] . "'," . PHP_EOL;
			$ckInstances .= "baseHref     : '" . MODX_SITE_URL . "'," . PHP_EOL;
			$ckInstances .= "contentsCss     : '" . $editor_css_path . "'," . PHP_EOL;
			$ckInstances .= "height     : '" . $params['height'] . "'," . PHP_EOL;
			if (!empty($params['format_tags'])) $ckInstances .= "format_tags     : '" . $params['format_tags'] . "'," . PHP_EOL;
			$ckInstances .= "customConfig : '" . MODX_BASE_URL . 'assets/plugins/' . $params['cke_dir'] . "/read_config.php?q=user_config.js'" . PHP_EOL;
			$ckInstances .= '}' . PHP_EOL;
			$ckInstances .= ' );' . PHP_EOL;
	
			$ckInstances .= "</script>" . PHP_EOL;
		}
		
		$ck_path = MODX_BASE_URL . 'assets/plugins/' . $params['cke_dir'] . '/ckeditor/ckeditor.js' . PHP_EOL;
		$script  = '		<script language="javascript" type="text/javascript" src="' . $ck_path . '"></script>' . PHP_EOL;
		$script .= '		<script language="javascript" type="text/javascript">' . PHP_EOL;
		$script .= '			function CKeditor_OnComplete(edtInstance) {' . PHP_EOL;
		$script .= '				if (edtInstance){ // to-do: add better listener' . PHP_EOL;
		$script .= '					edtInstance.AttachToOnSelectionChange(tvOnCKChangeCallback);' . PHP_EOL;
		$script .= '				}' . PHP_EOL;
		$script .= '			};' . PHP_EOL;
		$script .= '' . PHP_EOL;
		$script .= '			function tvOnCKChangeCallback(edtInstance) {' . PHP_EOL;
		$script .= '				if (edtInstance) {' . PHP_EOL;
		$script .= '					elm = edtInstance.LinkedField;' . PHP_EOL;
		$script .= '					if(elm && elm.onchange) elm.onchange();' . PHP_EOL;
		$script .= '				}' . PHP_EOL;
		$script .= '			}' . PHP_EOL;
		$script .= '		</script>' . PHP_EOL;
		$script .= $ckInstances;
		
		return $script;
	}
	
	function get_cke_lang($lang)
	{
		include_once($param['cke_path'] . 'lang/lang.php');
		$lang_sel = 'en';
		for ($i=0;$i<$cke_lang_count;$i++)
		{
			if($cke_lang[$i][0] == $lang)
			{
				$lang_sel = $cke_lang[$i][1];
			}
		}
		return $lang_sel;
	}
}