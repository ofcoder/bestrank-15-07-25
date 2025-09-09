<?php
    namespace Ofcode\UserRating\Helpers;
    defined('B_PROLOG_INCLUDED') || die;
    use Bitrix\Main\UserTable,
        Bitrix\Main\Localization\Loc;
    class UserHelper
    {
        public static function getList($params = []){
            return UserTable::getList($params);
        }
        public static function getAllActiveUsers()
        {
            $resUsers = self::getList([
                'select' => ['ID', 'NAME', 'LAST_NAME'],
                'filter' => ['ACTIVE' => 'Y']
            ]);
            
            $users = ['0' => Loc::getMessage('OFCODE_USERRATING_OPTIONS_EMPTY_SELECT')];
            while ($user = $resUsers->fetch()) {
                $users[(string)$user['ID']] = $user['NAME'] . ' ' . $user['LAST_NAME'];
            }
            return $users;
            
        }
    }