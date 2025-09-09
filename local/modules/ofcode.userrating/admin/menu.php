<?php
    defined('B_PROLOG_INCLUDED') || die;
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Bitrix\Main\Context;
// Получаем контекст текущего хита
    $context = Application::getInstance()->getContext();
// или
    $context = Context::getCurrent();

$arItems = [];

$arItems[] = [
    'parent_menu' => 'global_menu_user_rating',
    //'icon'        => 'fav_menu_icon',
    //'icon'        => 'adm-menu-setting',
    'icon'        => 'advertising_menu_icon',
    'page_icon'   => 'fav_menu_icon',
    'sort' => 100,
    'text' => Loc::getMessage('OFCODE_USERRATING_MENU_MAIN_SETTINGS_TEXT'),
    'url' => 'ofcode_userrating_options.php?lang=' . LANG,
    'items_id' => 'ofcode_rating_listelementdetail',
];
$arItems[] = [
    'parent_menu' => 'global_menu_user_rating',
    //'icon' => 'default_menu_icon',
    //'page_icon' => 'default_page_icon',
    //'icon' => 'clouds_menu_icon',
    'icon' => 'sale_menu_icon',
    'page_icon' => 'clouds_page_icon',
    'sort' => 101,
    'text' => Loc::getMessage('OFCODE_USERRATING_MENU_ADVANCED_SETTINGS_TEXT'),
    'url' => 'ofcode_userrating_advanced_options.php?lang=' . LANG,
    'items_id' => 'ofcode_rating_listelementdetail',
];

return $arItems;