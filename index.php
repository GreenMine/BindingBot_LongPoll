<?php

require_once('vendor/autoload.php');

include 'API.php';
include 'Configer.php';

use DigitalStar\vk_api\vk_api;
use DigitalStar\vk_api\LongPoll;

$config = new Configer('config.json');

$vk = vk_api::create($config->getVKInfo('token'), $config->getVKInfo('version'));
$vk = new LongPoll($vk);

$status = array();

$vk->listen(function ($data) use($vk, $config){
    global $status;
    $vk->initVars($id, $message, $payload);
    $api = new API(new \mysqli($config->getDBInfo('host'), $config->getDBInfo('username'), $config->getDBInfo('password'), $config->getDBInfo('dbname')), $config->getDBInfo('tablename'));
    $api->setId($id);
    switch ($data->type) {
        case 'message_new':
        $api_info = $api->getDBInfo();
        $message = trim($message);
            if (array_key_exists($id, $status)) {
                $api->setPlayer($message);
                $api_data = $api->getDBInfo();
                if(!empty($api_data)) {
                    if(empty($api_data[2])) {
                        $vk->reply('Вы успешно привязали аккаунт');
                        $vkinfo = $vk->userInfo($id);
                        $api->bindingAccount($message, $id, $vkinfo['first_name'], $vkinfo['last_name'], $vk->request('groups.isMember', ['group_id' => $data->group_id, 'user_id' => $id]));
                        unset($status[$id]);
                    }else {
                        $vk->reply('Данный пользователь уже привязан к другому аккаунту');
                    }
                }else {
                    $vk->reply('Данный пользователь не заходил на наш сервер.'.PHP_EOL.'Проверьте правильность введенного ника');
                }
            } else {
                if (isset($payload)) {
                    if ($payload['settings'] == 'binding') {
                        if(empty($api_info)) {
                            $vk->sendButton($id, 'Введите ник игрока на сервере, который вы хотите привязать', []);
                            $status[$id] = true;
                        }else {
                            $vk->reply('Ваш аккаунт уже привязан к пользователю с ником '. $api_info[1]);
                        }
                    }
                } else {
                    $vk->sendButton($id, 'Привязать', [
                        [[["settings" => 'binding'], 'Привязать', 'green']]
                    ]);
                }
            }
        break;
        case 'group_join':
            $vk->reply('Спасибо за подписку!'.PHP_EOL.'Приятной игры на нашем сервере!');
            if($api->checkBinding()) {
                $api->updateSubscribe(1);
            }
            break;
        case 'group_leave':
            $api->updateSubscribe(0);
            break;
    }
});