<?php
/*
*Самодельные функции записи массива в файл 
*и дальнейшего преобразования его из этого файла\строки обратно в массив
*/
$dossier = array(
	'name'=>'Ilat',
	'age' =>33,
	'city'=>'Dnepropetrovsk',
	'sex' =>'male',
	11,12,13,14,
	'wife'=>['Irina', 'Oksana', 'Lera', 'Svetlana']
);
echo count($dossier, COUNT_RECURSIVE);
function array_in_string($arr){
	
	$i = 1;
	foreach($arr as $k=>$v){
		if($i >sizeof($arr))break;
		file_put_contents('array_in_string.txt', "$k|$v,", FILE_APPEND);
		$i++;
	}
}

array_in_string($dossier);

function from_string_in_array($f_name){
	$arr_prepare = explode(',', file_get_contents($f_name));
	$arr_res = [];
    $i = 2;
	foreach($arr_prepare as $v){
		if($i >sizeof($arr_prepare))break;
		$arr_res[explode('|', $v)[0]] = explode('|', $v)[1];
		$i++;
	}
	return $arr_res;
}

$r = from_string_in_array('array_in_string.txt');
print_r($r);

//==============================================================================

/*
*Инструменты для распарсивания текстового файла
*/
$xml = file_get_contents('name.txt');
$finale = [];
$finale2 = [];
$res = explode(',',$xml);
//print_r($res);
foreach($res as $v){
	$finale[] = explode('":"', $v)[0];
	//$finale2[] = explode('">', $v)[1];
}
print_r($finale);
$niche_name = [];
foreach($finale as $v){
	$niche_name[] = $v;
}
//print_r($niche_name);
$finale_res = [];

$i = 0;
echo '{';
foreach($finale as $v){
echo '"' . $finale2[$i] . '":"' . trim($v) . '",' . '<br>';
	$i++;
}
echo '}';

//==============================================================================
/*
*Функция для отладки скриптов
*/
## Функция для вывода содержимого переменной
 // Распечатывает дамп переменной на экран
 function dumper($obj){
    echo
    "<pre>",
    htmlspecialchars(dumperGet($obj)),
    "</pre>";
 }
 // Возвращает строку - дамп значения переменной в древовидной форме
 // (если это массив или объект). В переменной $leftSp хранится
 // строка с пробелами, которая будет выводиться слева от текста.
 function dumperGet(&$obj, $leftSp = ""){
    if (is_array($obj)) {
        $type = "Array[".count($obj)."]";
    } elseif (is_object($obj)) {
        $type = "Object";
    } elseif (gettype($obj) == "boolean") {
        return $obj? "true" : "false";
    } else {
        return "\"$obj\"";
    }
 $buf = $type;
 $leftSp .= " ";
 for (Reset($obj); list($k, $v) = each($obj); ) {
 	if ($k === "GLOBALS") continue;
 	$buf .= "\n$leftSp$k => ".dumperGet($v, $leftSp);
 	}
 return $buf;
 } 

//==============================================================================
/*
*Эмуляция virtual() в CGI-версии PHP
*/
// Функция virtual() не поддерживается?
if (!function_exists("virtual")) {
    // Тогда определяем свою
    echo "virtual";
    function virtual($uri){
        $url = "http://".$_SERVER["HTTP_HOST"].$uri;
        echo file_get_contents($url);
    }
}
// Пример - выводит корневую страницу сайта
virtual("/"); 

//==============================================================================
/*
*Parsing csv
*/
function search_video_in_dump_csv($needle){
	if(!isset($initial_actions)){
		$prepare_data;
		$final_res = [];
		$initial_actions = 1;
}

function my_gen(){
	if(($handle = fopen("dump.csv", "r")) !== FALSE) {
       	while(($data = fgetcsv($handle, 3000, PHP_EOL)) !== FALSE) {
       		$num = count($data);
       		for($c=0; $c < $num; $c++) {
           		yield $prepare_data = explode('|', $data[$c]);
       		}
    	}
    fclose($handle);
	}
}

$my_gen = my_gen();

foreach($my_gen as $v){
	if(strpos($v[4], $needle) !== false){
		$final_res[] = [
			'id'       => $v[0],
			'site'     => $v[1],
			'mp4_url'  => $v[2],
			'title'    => $v[3],
			'tag'      => $v[4],
			'actors'   => $v[5],
			'duration' => $v[6],
			'scrin_url'=> explode(';', $v[8])[0],
		];
	}
}
return $final_res;
}

//==============================================================================

/**Получение заголовков ответа сервера**/ 
  if($curl = curl_init()) {
        curl_setopt($curl,CURLOPT_URL, 'http://example.com');
        curl_setopt($curl, CURLOPT_REFERER, 'http://example.com');
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_NOBODY,true);
        curl_setopt($curl,CURLOPT_HEADER,true);

        $out = curl_exec($curl);
        $out = explode("\n", $out);
        curl_close($curl);
    }
//==============================================================================
/** SOAP */
//client
ini_set("soap.wsdl_cache_enabled", "0");
$client = new SoapClient("http://example.com/news.wsdl");
print_r($client->__getFunctions());
$result1 = $client->one(1);
echo '<br>' . $result1 . '<br>';
$result2 = $client->two();
echo $result2. '<br>';
$result3 = $client->three(1);
echo $result3. '<br>';

//wsdl
/*
<?xml version ='1.0' encoding ='UTF-8' ?>
<definitions name='News'
             targetNamespace='http://example.com'
             xmlns:tns='http://example.com'
             xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/'
             xmlns:xsd='http://www.w3.org/2001/XMLSchema'
             xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/'
             xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'
             xmlns='http://schemas.xmlsoap.org/wsdl/'>

  <message name='oneRequest'>
    <part name='id' type='xsd:integer'/>
  </message>
  <message name='oneResponse'>
    <part name='item' type='xsd:integer'/>
  </message>

  <message name='twoResponse'>
    <part name='count' type='xsd:integer'/>
  </message>

  <message name='threeRequest'>
    <part name='cat_id' type='xsd:integer'/>
  </message>
  <message name='threeResponse'>
    <part name='count' type='xsd:integer'/>
  </message>

  <portType name='NewsPortType'>
    <operation name='one'>
      <input message='tns:oneRequest'/>
      <output message='tns:oneResponse'/>
    </operation>
    <operation name='two'>
      <output message='tns:twoResponse'/>
    </operation>
    <operation name='three'>
      <input message='tns:threeRequest'/>
      <output message='tns:threeResponse'/>
    </operation>
  </portType>

  <binding name='NewsBinding' type='tns:NewsPortType'>
    <soap:binding style='rpc' transport='http://schemas.xmlsoap.org/soap/http'/>
    <operation name='one' />
    <operation name='two' />
    <operation name='three' />
  </binding>

  <service name='NewsService'>
    <port name='NewsPort' binding='NewsBinding'>
      <soap:address location='http://example.com/soap-server.php'/>
    </port>
  </service>
</definitions>
*/

//server
class NewsService{

    /* Метод возвращает новость по её идентификатору */
    function one($id){
        setcookie('key', 'value');
       return $id + 9999;
    }
    /* Метод считает количество всех новостей */
    function two(){
        return 333;
    }
    /* Метод считает количество новостей в указанной категории */
    function three($cat_id){
        return $cat_id + 1;
    }
}
// Отключение кеширования wsdl-документа
//ini_set("soap.wsdl_cache_enabled", 0);
// Создание SOAP-сервера
$server = new SoapServer("http://newsoap.seolab.dp.ua/news.wsdl");
// Регистрация класса
$server->setClass("NewsService");
// Запуск сервера
$server->handle();

//==============================================================================
//Скачивание больших файлов
$file_resource = fopen('video.mp4', 'w+');

if( $curl = curl_init() ) {
    curl_setopt($curl, CURLOPT_URL, 'http://example.com/file.mp4');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl, CURLOPT_FILE, $file_resource);
    curl_exec($curl);
    curl_close($curl);
  }

//==============================================================================
//Преобразование из hex в RGB
$hex = "#ffffff";
list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
echo "$hex -> $r $g $b";

//==============================================================================
 Удаление директории \ Удаление папки с файлами 
function emptyDir($dir) {
    if (is_dir($dir)) {
        $scn = scandir($dir);
        foreach ($scn as $files) {
            if ($files !== '.') {
                if ($files !== '..') {
                    if (!is_dir($dir . '/' . $files)) {
                        unlink($dir . '/' . $files);
                    } else {
                        emptyDir($dir . '/' . $files);
                        rmdir($dir . '/' . $files);
                    }
                }
            }
        }
    }
}

$dir = 'folder_for_delete';
emptyDir($dir);
rmdir($dir);

//==============================================================================
Система логирования ошибок 
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set("highlight.default", "#0000BB");
set_error_handler('my_error');
register_shutdown_function('shutdown');

function my_error($errno, $errstr, $errfile, $errline)
{
    if (in_array($errno, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR]) === true)
        return false;

    $f = fopen($errfile, "r");
    $lines = [];
    $i = 1;
    while ($line = fgets($f)) {
        $lines[$i++] = $line;
    }
    fclose($f);

    for ($i = 10, $start = 0; $start <= 0; $i--) {
        $start = $errline - $i;
    }

    for ($i = 10, $end = 0; $end <= 0; $i--) {
        $end = $errline + $i;
        if ($end <= count($lines)) {
            break;
        }
        $end = 0;
    }


    $code = '';
    foreach($lines as $k => $v){
        if ($k >= $start && $k <= $end) {
            $code .= $k . ' ' .$v;
        }
    }
    $code = highlight_string($code, true);
    $date = date('Y-m-d H:i:s');
    $http_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    $country_code = isset($_SERVER['HTTP_GEOIP_COUNTRY_CODE']) ? $_SERVER['HTTP_GEOIP_COUNTRY_CODE'] : null;
    $str =
        '<div style="background-color: #4F5B93; color: #E2E4EF; padding: 12px">' .
        '<p>Main information</p>' .
        PHP_EOL . '<br>' .
        'Date: ' . $date .
        PHP_EOL . '<br>' .
        'Error number: ' . $errno .
        PHP_EOL . '<br>' .
        'Error text: ' . $errstr .
        PHP_EOL . '<br>' .
        'File: ' . $errfile .
        PHP_EOL . '<br>' .
        'Line number: ' . $errline .
        PHP_EOL . '<br>' .
        '</div>' .

        '<div style="background-color: #F2F2F2; padding: 12px;">' .
        '<p>SERVER variable</p>' .
        'Browser: ' . $_SERVER['HTTP_USER_AGENT'] .
        PHP_EOL . '<br>' .
        'Country code: ' . $country_code .
        PHP_EOL . '<br>' .
        'Method: ' .  $_SERVER['REQUEST_METHOD'] .
        PHP_EOL . '<br>' .
        'Page refferer: ' . $http_referer .
        PHP_EOL . '<br>' .
        '</div>' .

        '<div style="background-color: #ffffff; box-shadow: inset 0 0 0 1px rgba(0,0,0,.15); padding: 12px">' .
        PHP_EOL . '<br>' .
        '<p>CODE</p>' .
        PHP_EOL . '<br>' .
        $code .
        PHP_EOL . '<br>' .
        PHP_EOL . '<br>' .
        '</div>' .
        PHP_EOL . '<br>' .
        '<hr>' .
        PHP_EOL . '<br>';

    file_put_contents('log.html', $str, FILE_APPEND);
    return true;
}

function shutdown()
{
    $error = error_get_last();
    $f = fopen($error['file'], "r");
    $lines = [];
    $i = 1;
    while ($line = fgets($f)) {
        $lines[$i++] = $line;
    }
    fclose($f);

    for ($i = 10, $start = 0; $start <= 0; $i--) {
        $start = $error['line'] - $i;
    }

    for ($i = 10, $end = 0; $end <= 0; $i--) {
        $end = $error['line'] + $i;
        if ($end <= count($lines)) {
            break;
        }
        $end = 0;
    }


    $code = '';
    foreach($lines as $k => $v){
        if ($k >= $start && $k <= $end) {
            $code .= $k . ' ' .$v;
        }
    }
    $code = highlight_string($code, true);
    $date = date('Y-m-d H:i:s');
    $http_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    $country_code = isset($_SERVER['HTTP_GEOIP_COUNTRY_CODE']) ? $_SERVER['HTTP_GEOIP_COUNTRY_CODE'] : null;
    $str =
        '<div style="background-color: #4F5B93; color: #E2E4EF; padding: 12px">' .
        '<p>Main information</p>' .
        'Date: ' . $date .
        PHP_EOL . '<br>' .
        'Error number: ' . $error['type'] .
        PHP_EOL . '<br>' .
        'Error text: ' . $error['message'] .
        PHP_EOL . '<br>' .
        'File: ' . $error['file'] .
        PHP_EOL . '<br>' .
        'Line number: ' . $error['line'] .
        PHP_EOL . '<br>' .
        '</div>' .

        '<div style="background-color: #F2F2F2; padding: 12px;">' .
        '<p>SERVER variable</p>' .
        'Browser: ' . $_SERVER['HTTP_USER_AGENT'] .
        PHP_EOL . '<br>' .
        'Country code: ' . $country_code .
        PHP_EOL . '<br>' .
        'Method: ' .  $_SERVER['REQUEST_METHOD'] .
        PHP_EOL . '<br>' .
        'Page refferer: ' . $http_referer .
        PHP_EOL . '<br>' .
        '</div>' .

        '<div style="background-color: #ffffff; box-shadow: inset 0 0 0 1px rgba(0,0,0,.15); padding: 12px">' .
        PHP_EOL . '<br>' .
        '<p>CODE</p>' .
        PHP_EOL . '<br>' .
        $code .
        PHP_EOL . '<br>' .
        PHP_EOL . '<br>' .
        '</div>' .
        PHP_EOL . '<br>' .
        '<hr>' .
        PHP_EOL . '<br>';

    if (
        // если в коде была допущена ошибка
        is_array($error) &&
        // и это одна из фатальных ошибок
        in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])
    ) {
        // очищаем буфер вывода (о нём мы ещё поговорим в последующих статьях)
        while (ob_get_level()) {
            ob_end_clean();
        }
        // выводим описание проблемы
        echo 'Сервер находится на техническом обслуживании, зайдите позже';
        //Записываем данные в файл
        file_put_contents('log.html', $str, FILE_APPEND);
    }
}
