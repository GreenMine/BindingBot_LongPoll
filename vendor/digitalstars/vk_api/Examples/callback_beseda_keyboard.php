<?php
/** данные подключения правильные только в том случае,
* если вы запускаете скрипт из папки Examples  из только что установленой либы. 
* Иначе путь до autoload будет другоц
*/
//require_once('../vendor/autoload.php'); //подключаем библу ЧЕРЕЗ COMPOSER
require_once('../autoload.php'); //подключаем библу
use DigitalStar\vk_api\vk_api;

//**********CONFIG**************
const VK_KEY = ""; //ключ авторизации сообщества, который вы получили
const CONFIRM_STR = ""; //ключ авторизации сообщества, который вы получили
const VERSION = "5.95"; //ваша версия используемого api
const INFO =  [["command" => 'info'], "Информация", "blue"]; //Код кнопки 'info'
//******************************

$vk = vk_api::create(VK_KEY, VERSION)->setConfirm(CONFIRM_STR);
$vk->debug();
$vk->initVars($id, $message, $payload); //инициализация переменных

if ($payload) { //если пришел payload
    if($payload['command'] == 'info')
        $vk->reply('Секретная информация'); //отвечает пользователю или в беседу
} else
    $vk->sendButton($id, 'Отправил кнопку', [[INFO]]); //отправляем клавиатуру с сообщением
