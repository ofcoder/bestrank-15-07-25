<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
    
    use Bitrix\Main\Loader,
        Ofcode\UserRating\Helpers;
    
    Loader::includeModule('ofcode.userrating');
    
    require_once(Helpers\Options::getModuleDir(true) . 'admin/ofcode_userrating_options.php');