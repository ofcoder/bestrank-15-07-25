<?php
defined('B_PROLOG_INCLUDED') || die;
define("NEED_AUTH", true);
use Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Main\Localization\Loc,
    Ofcode\UserRating\Helpers,
    Ofcode\UserRating\Helpers\RenderOptions,
    Ofcode\UserRating\Helpers\Options,
    Ofcode\UserRating\Helpers\UserHelper;

global $APPLICATION, $USER;

if (!$USER->IsAdmin())
    return;

$mid = Options::getModuleId();
$request = Application::getInstance()->getContext()->getRequest();
$tabs[] = [
    'DIV' => 'second',
    'TAB' => Loc::getMessage('OFCODE_USERRATING_SECOND_TAB'),
    'ICON' => 'dis_settings',
    'TITLE' => Loc::getMessage('OFCODE_USERRATING_SECOND_TITLE')
];

//Получение списка пользователей [id => Имя Фамилия]
$users = UserHelper::getAllActiveUsers();

//Массив настроек ключ - $tabs['DIV']
$options = [
    'second' => [

        [
            'users',
            Loc::getMessage('OFCODE_USERRATING_OPTIONS_USERS_LIST'),
            //$users[0],
            '3',
            ['multiselectbox', $users]
        ],

    ]
];

if (check_bitrix_sessid() && (strlen($request->getPost('save')) > 0 || strlen($request->getPost('apply')) > 0)) {

    if (!is_array($options))
        return false;

    foreach ($options as $arOptions) {
        RenderOptions::__AdmSettingsSaveOptions($mid, $arOptions);
    }

    if (strlen($request->getPost('save')) > 0) {
        LocalRedirect($request->getRequestUri());
    }
}
//Подключаем заголовок модуля
$APPLICATION->SetTitle(Loc::getMessage('OFCODE_USERRATING_ADVANCED_SETTINGS_TITLE', ['#MODULE_ID#' => $mid]));

require(Application::getDocumentRoot() . '/bitrix/modules/main/include/prolog_admin_after.php');

$tabControl = new CAdminTabControl('tabControl', $tabs);
$tabControl->Begin();
?>
    <form method='POST' action='<?= $request->getRequestUri() ?>'>
        <?php
        foreach ($options as $option) {
            $tabControl->BeginNextTab();
            Helpers\RenderOptions::__AdmSettingsDrawList($mid, $option);
        }
        $tabControl->Buttons(['btnApply' => true, 'btnCancel' => false, 'btnSaveAndAdd' => false]);
        echo bitrix_sessid_post();
        $tabControl->End();
        ?>
    </form>
    <p><?=GetMessage("AUTH_SUCCESS")?></p>
    <p><a href="<?=SITE_DIR?>"><?=GetMessage("AUTH_BACK")?></a></p>
<?php
require(Application::getDocumentRoot() . '/bitrix/modules/main/include/epilog_admin.php');
