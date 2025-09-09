<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Loader,
    Bitrix\Main\ModuleManager,
    Bitrix\Main\Diag\Debug,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Config\Option,
    Bitrix\Main\HttpApplication,
    Ofcode\Userrating\Helpers\Options,
    Ofcode\Userrating\Helpers\IblockHelper,
    Ofcode\Userrating\Helpers\UserHelper;

global $APPLICATION;
Loc::loadMessages(__FILE__);

// получение запроса из контекста для обработки данных
$request = HttpApplication::getInstance()->getContext()->getRequest();

// получаем id модуля
//$module_id = 'ofcode.userrating';
//$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);
$module_id = Options::getModuleId();

// получим права доступа текущего пользователя на модуль
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);

// если нет прав - отправим к форме авторизации с сообщением об ошибке
if ($POST_RIGHT < "S") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

if (!Loader::includeModule($module_id)
    || !Loader::includeModule('iblock')) {
    return;
}

//region Массив для вкладок
$tabs = [
    [
        // значение будет вставлено во все элементы вкладки для идентификации (используется для javascript)
        'DIV' => 'first',
        'TAB' => Loc::getMessage('OFCODE_USERRATING_TAB_FIRST_NAME'),
        'ICON' => 'dis_settings',
        'TITLE' => Loc::getMessage('OFCODE_USERRATING_TAB_FIRST_TITLE')
    ],
    [
        'DIV' => 'second',
        'TAB' => Loc::getMessage('OFCODE_USERRATING_TAB_SECOND_NAME'),
        'ICON' => 'dis_settings',
        'TITLE' => Loc::getMessage('OFCODE_USERRATING_TAB_SECOND_TITLE')
    ]
];

//Получение списка инфоблоков [id => CODE]
$iBlocks = IblockHelper::iblockActiveGetList();

//Получение списка пользователей [id => Имя Фамилия]
$users = UserHelper::getAllActiveUsers();

//Массив настроек ключ - $tabs['DIV']
$arOptions = [
    'first' => [
        //region Настройка для чек-бокса
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
        //endregion
        //region Настройка для выпадающего списка
        [
            'mainIblock',
            Loc::getMessage('OFCODE_USERRATING_OPTIONS_MAIN_IBLOCK'),
            '',
            //$iBlocks[2],
            [
                'selectbox',
                $iBlocks
            ]
        ],
        //endregion
        //region Настройка комментарий в желтом блоке
        [
            'note' => Loc::getMessage('OFCODE_USERRATING_OPTIONS_YELLOW_TEXT')
        ],
        //endregion
    ],
    'second' => [
        //region Настройка для многострочного списка
        [
            'users',
            Loc::getMessage('OFCODE_USERRATING_OPTIONS_USERS_LIST'),
            //$users[0],
            '3',
            ["multiselectbox", $users]
        ],
        //endregion
    ]
];

//region Cохранение формы

$isSave = $request->isPost() && !empty($request['save']);
$isApply = $request->isPost() && !empty($request['apply']);

if (check_bitrix_sessid()
    && $request->isPost()
    && ($isSave || $isApply)
) {

    foreach ($arOptions as $option) {
        __AdmSettingsSaveOptions($module_id, $option);
    }
    if ($isSave) {
        LocalRedirect($APPLICATION->GetCurPageParam());
    }
}

//endregion
//region Конструктор формы
#Визуальный вывод
$tabControl = new CAdminTabControl('tabControl', $tabs);
$tabControl->Begin();
?>

<form method="POST"
      action="<?php echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($module_id) ?>&lang=<?= LANGUAGE_ID ?>"
      id="baseexchange_form">
    <?php

    foreach ($arOptions as $option) {
        $tabControl->BeginNextTab();
        __AdmSettingsDrawList($module_id, $option);
    }
    $tabControl->Buttons(array('btnApply' => false, 'btnCancel' => false, 'btnSaveAndAdd' => false));
    echo bitrix_sessid_post();
    $tabControl->End();
    ?>
</form>
