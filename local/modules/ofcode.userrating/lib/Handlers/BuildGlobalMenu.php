<?php
    namespace Ofcode\UserRating\Handlers;
    defined('B_PROLOG_INCLUDED') || die;
    use Bitrix\Main\Localization\Loc;
    class BuildGlobalMenu
    {
        /**
         * Добавление глобального раздела в меню админки
         * @param $aGlobalMenu
         * @param $aModuleMenu
         */
        public static function addMenuItem(&$aGlobalMenu, &$aModuleMenu)
        {
            global $USER;
            
            if (!$USER->IsAdmin() || is_array($aGlobalMenu['global_menu_user_rating']))
                return;
            
            $aGlobalMenu['global_menu_user_rating'] = [
                "menu_id" => "user_rating",
                "text" => Loc::getMessage('OFCODE_USERRATING_GLOBAL_MENU_USER_RATING_TEXT'),
                "title" => Loc::getMessage('OFCODE_USERRATING_GLOBAL_MENU_USER_RATING_TITLE'),
                "icon"        => "form_menu_icon", // малая иконка
                "page_icon"   => "form_page_icon", // большая иконка
                "sort" => 1000,
                "items_id" => "global_menu_user_rating",
                "items" => []
            ];
            // Убрать "Рабочий стол"
            //unset($aGlobalMenu["global_menu_desktop"]);

        }
    }