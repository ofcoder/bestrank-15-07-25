<?php
defined('B_PROLOG_INCLUDED') || die;
define("NEED_AUTH", true);

use Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Main\Localization\Loc,
    Ofcode\UserRating\Helpers,
    Ofcode\UserRating\Helpers\RenderOptions,
    Ofcode\UserRating\Helpers\Options,
    Ofcode\UserRating\Helpers\IblockHelper,
    Ofcode\UserRating\Helpers\UserHelper;

global $APPLICATION, $USER;

if (!$USER->IsAdmin())
    return;

$mid = Options::getModuleId();
$request = Application::getInstance()->getContext()->getRequest();
$options = [];
$tabs = [];

$arUsers = UserHelper::getAllActiveUsers();

$tabs[] = [
    'DIV' => 'first',
    'TAB' => Loc::getMessage('OFCODE_USERRATING_TAB_FIRST_NAME'),
    'ICON' => 'dis_settings',
    'TITLE' => Loc::getMessage('OFCODE_USERRATING_TAB_FIRST_TITLE')
];

//Получение списка инфоблоков [id => CODE]
$iBlocks = IblockHelper::iblockActiveGetList();

//Получение списка пользователей [id => Имя Фамилия]
$users = UserHelper::getAllActiveUsers();

//Массив настроек ключ = $tabs['DIV']
$options = [
    'first' => [
        [
            'isModuleActive',
            Loc::getMessage('OFCODE_USERRATING_OPTIONS_IS_MODULE_ACTIVE'),
            'Y',
            [
                'checkbox',
                0,
                'title="ага" data="somedata"'
            ],
            'N',
            Loc::getMessage('OFCODE_USERRATING_OPTIONS_IS_MODULE_ACTIVE_RED'),
            'N'
        ],
        [
            'mainIblock',
            Loc::getMessage('OFCODE_USERRATING_OPTIONS_MAIN_IBLOCK'),
            '3',
            [
                'selectbox',
                $iBlocks
            ]
        ],
        //region Настройка комментарий в желтом блоке
        [
            'note' => Loc::getMessage('OFCODE_USERRATING_OPTIONS_YELLOW_TEXT')
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
$APPLICATION->SetTitle(Loc::getMessage('OFCODE_USERRATING_MAIN_SETTINGS_TITLE', ['#MODULE_ID#' => $mid]));

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
    <p><?= GetMessage("AUTH_SUCCESS") ?></p>
    <p><a href="<?= SITE_DIR ?>"><?= GetMessage("AUTH_BACK") ?></a></p>
<?php
require(Application::getDocumentRoot() . '/bitrix/modules/main/include/epilog_admin.php');
