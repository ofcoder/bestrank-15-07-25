<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;


class ofcode_userrating extends CModule
{
    // ID модуля
    var $MODULE_ID = "ofcode.userrating";

    //Версия модуля
    var $MODULE_VERSION;
    //Дата последней версии модуля
    var $MODULE_VERSION_DATE;
    //Название модуля
    var $MODULE_NAME;

    //Описание модуля
    var $MODULE_DESCRIPTION;

    private $errors = [];

    //Конструктор модуля
    function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__) . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = Loc::getMessage('ST_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('ST_MODULE_DESC');
        $this->PARTNER_NAME = Loc::getMessage('ST_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('ST_PARTNER_URL');
    }

    //Устаовка модуля
    function DoInstall()
    {
        global $APPLICATION;
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallEvents();
        $this->InstallDB();
        $this->InstallFiles();

        $APPLICATION->includeAdminFile(
            Loc::getMessage('USER_RATING_INSTALL_TITLE'),
            $this->getPath() . '/install/step1.php'
        );
    }
    //Удаление модуля
    function DoUninstall()
    {
        global $APPLICATION, $step;
        $step = intval($step);
        if ($step < 2) {
            $APPLICATION->includeAdminFile(
                Loc::getMessage('USER_RATING_UNINSTALL_TITLE'),
                $this->getPath() . '/install/unstep1.php'
            );
        } elseif ($step == 2) {
            $this->UnInstallEvents();
            $this->uninstallDB();
            $this->uninstallFiles();

            ModuleManager::unRegisterModule($this->MODULE_ID);
            $APPLICATION->includeAdminFile(
                Loc::getMessage('USER_RATING_UNINSTALL_TITLE'),
                $this->getPath() . '/install/unstep2.php'
            );
        }
    }

    //Установка обраотчиков события
    function InstallEvents()
    {

        return true;
    }

    //Удаление обработчиков событий
    function UnInstallEvents()
    {

        return true;
    }

    //Установка файлов
    function InstallFiles()
    {

        return true;
    }

    //Удаление файлов
    function UnInstallFiles()
    {


        return true;
    }

    //Заполнение данных в базу данных
    function InstallDB()
    {

        return true;
    }

    //Удаление данных из базе данных
    function UnInstallDB()
    {

        return true;
    }

    //Обработка места установки модуля
    protected function getPath($notDocumentRoot = false)
    {
        $path = dirname(__DIR__);
        $path = str_replace("\\", "/", $path);
        return ($notDocumentRoot)
            ? preg_replace("#^(.*)\/(local|bitrix)\/modules#", '/$2/modules', $path)
            : $path;
    }
}