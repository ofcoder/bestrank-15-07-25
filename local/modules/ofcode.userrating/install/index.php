<?php
    
    use Bitrix\Main\Loader;
    use Bitrix\Main\Localization\Loc;
    use Bitrix\Main\ModuleManager;
    
    
    class ofcode_userrating extends CModule
    {
        // ID модуля
        public $MODULE_ID;
        
        //Версия модуля
        public $MODULE_VERSION;
        //Дата последней версии модуля
        public $MODULE_VERSION_DATE;
        //Название модуля
        public $MODULE_NAME;
        
        //Описание модуля
        public $MODULE_DESCRIPTION;
        
        public $SHOW_SUPER_ADMIN_GROUP_RIGHTS;
        public $MODULE_GROUP_RIGHTS;
        public $errors;
        
        //Конструктор модуля
        function __construct()
        {
            $arModuleVersion = array();
            include(dirname(__FILE__) . '/version.php');
            $this->MODULE_ID = Loc::getMessage('OFCODE_USERRATING_MODULE_ID');
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            
            $this->MODULE_NAME = Loc::getMessage('OFCODE_USERRATING_MODULE_NAME');
            $this->MODULE_DESCRIPTION = Loc::getMessage('OFCODE_USERRATING_MODULE_DESC');
            $this->PARTNER_NAME = Loc::getMessage('OFCODE_USERRATING_PARTNER');
            $this->PARTNER_URI = Loc::getMessage('OFCODE_USERRATING_PARTNER_URL');
            // если указано, то на странице прав доступа будут показаны администраторы и группы
            $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
            // если указано, то на странице редактирования групп будет отображаться этот модуль
            $this->MODULE_GROUP_RIGHTS = 'Y';
        }
        
        public function isVersionD7()
        {
            return CheckVersion(ModuleManager::getVersion('main'), '20.00.00');
        }
        
        //Устаовка модуля
        function DoInstall()
        {
            try {
                global $APPLICATION;
                if ($this->isVersionD7()) {
                    ModuleManager::registerModule($this->MODULE_ID);
                    
                } else {
                    $APPLICATION->ThrowException(Loc::getMessage('OFCODE_USERRATING_INSTALL_ERROR_VERSION'));
                    return false;
                }
                if (!$this->InstallEvents()){
                    $APPLICATION->ThrowException(Loc::getMessage('OFCODE_USERRATING_INSTALL_ERROR_EVENTS'));
                    return false;
                }
                if (!$this->InstallDB()){
                    $APPLICATION->ThrowException(Loc::getMessage('OFCODE_USERRATING_INSTALL_ERROR_DB'));
                    return false;
                }
                if (!$this->InstallFiles()){
                    $APPLICATION->ThrowException(Loc::getMessage('OFCODE_USERRATING_INSTALL_ERROR_FILES'));
                    return false;
                }
                
                $APPLICATION->includeAdminFile(
                    Loc::getMessage('OFCODE_USERRATING_INSTALL_TITLE'),
                    $this->getPath() . '/install/step1.php'
                );
                return true;
            } catch (Exception $e) {
                global $APPLICATION;
                $APPLICATION->ThrowException($e->getMessage());
                
                return false;
            }
           
        }
        
        //Удаление модуля
        function DoUninstall()
        {
            try {
                global $APPLICATION, $step;
                $step = intval($step);
                if ($step < 2) {
                    $APPLICATION->includeAdminFile(
                        Loc::getMessage('OFCODE_USERRATING_UNINSTALL_TITLE'),
                        $this->getPath() . '/install/unstep1.php'
                    );
                } elseif ($step == 2) {
                    if (!$this->UnInstallEvents()){
                        $APPLICATION->ThrowException(Loc::getMessage('OFCODE_USERRATING_UNINSTALL_ERROR_EVENTS'));
                        return false;
                    }
                    if (!$this->UnInstallDB()){
                        $APPLICATION->ThrowException(Loc::getMessage('OFCODE_USERRATING_UNINSTALL_ERROR_DB'));
                        return false;
                    }
                    if (!$this->UnInstallFiles()){
                        $APPLICATION->ThrowException(Loc::getMessage('OFCODE_USERRATING_UNINSTALL_ERROR_FILES'));
                        return false;
                    }
                    
                    ModuleManager::unRegisterModule($this->MODULE_ID);
                    $APPLICATION->includeAdminFile(
                        Loc::getMessage('OFCODE_USERRATING_UNINSTALL_TITLE'),
                        $this->getPath() . '/install/unstep2.php'
                    );
                }
                return true;
            } catch (Exception $e) {
                global $APPLICATION;
                $APPLICATION->ThrowException($e->getMessage());
                
                return false;
            }
            
        }
        
        //Установка обраотчиков события
        function InstallEvents()
        {
            try {
                
                return true;
                
            } catch (Exception $e) {
                global $APPLICATION;
                $APPLICATION->ThrowException($e->getMessage());
                
                return false;
            }
            
        }
        
        //Удаление обработчиков событий
        function UnInstallEvents()
        {
            try {
                
                return true;
                
            } catch (Exception $e) {
                global $APPLICATION;
                $APPLICATION->ThrowException($e->getMessage());
                
                return false;
            }
        }
        
        //Установка файлов
        function InstallFiles()
        {
            try {
                
                return true;
                
            } catch (Exception $e) {
                global $APPLICATION;
                $APPLICATION->ThrowException($e->getMessage());
                
                return false;
            }
        }
        
        //Удаление файлов
        function UnInstallFiles()
        {
            try {
                
                return true;
                
            } catch (Exception $e) {
                global $APPLICATION;
                $APPLICATION->ThrowException($e->getMessage());
                
                return false;
            }
        }
        
        //Заполнение данных в базу данных
        function InstallDB()
        {
            try {
                
                return true;
                
            } catch (Exception $e) {
                global $APPLICATION;
                $APPLICATION->ThrowException($e->getMessage());
                
                return false;
            }
        }
        
        //Удаление данных из базы данных
        function UnInstallDB()
        {
            try {
                if (Bitrix\Main\Config\Option::get( "askaron.settings", "UF_MODULE_USERRATING_NOT_DELETE") !== "1"
                    //&& Bitrix\Main\Engine\CurrentUser::get()->getId() !== 1
                )
                {
                    return false;
                }
                return true;
                
            } catch (Exception $e) {
                global $APPLICATION;
                $APPLICATION->ThrowException($e->getMessage());
                
                return false;
            }
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