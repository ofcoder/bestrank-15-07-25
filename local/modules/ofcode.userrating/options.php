<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader,
    Bitrix\Main\ModuleManager,
    Bitrix\Main\Diag\Debug,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Config\Option,
    Bitrix\Main\HttpApplication;

global $APPLICATION;
Loc::loadMessages(__FILE__);
// получение запроса из контекста для обработки данных
$request = HttpApplication::getInstance()->getContext()->getRequest();
//$module_id = 'ofcode.userrating';
// получение запроса из контекста для обработки данных
$request = HttpApplication::getInstance()->getContext()->getRequest();

// получаем id модуля
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

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
$tabs =[
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
/**
 * CControllerClient::GetInstalledOptions($module_id);
 * Формат массива, элементы:
 * 1) ID опции (id инпута)(Берется с помощью COption::GetOptionString($module_id, $Option[0], $Option[2])
 * если есть)
 * 2) Отображаемое название опции
 * 3) Значение по умолчанию (так же берется если первый элемент равен пустой строке), зависит от типа:
 *      checkbox - Y если выбран
 *      text/password - htmlspecialcharsbx($val)
 *      selectbox - одно из значений, указанных в массиве опций
 *      multiselectbox - значения через запятую, указанные в массиве опций
 * 4) Тип поля (массив)
 *      1) Тип (multiselectbox, textarea, statictext, statichtml, checkbox, text, password, selectbox)
 *      2) Зависит от типа:
 *         text/password - атрибут size
 *         textarea - атрибут rows
 *         selectbox/multiselectbox - массив опций формата ["Значение"=>"Название"]
 *      3) Зависит от типа:
 *         checkbox - доп атрибут для input (просто вставляется строкой в атрибуты input)
 *         textarea - атрибут cols
 *
 *          noautocomplete) для text/password, если true то атрибут autocomplete="new-password"
 *
 * 5) Disabled = 'Y' || 'N';
 * 6) $sup_text - ??? текст маленького красного примечания над названием опции
 * 7) $isChoiceSites - Нужно ли выбрать сайт??? флаг Y или N
 */
/*
$arOptions=[
    'first'=>[
        //region Настройка для чек-бокса
        [
            'myCheckBox',
            'Название чек-бокса',
            'Y',
            [
                "checkbox",
                0,
                'title="ага" data="somedata"'
            ],
            'N',
            'Красный текст',
            'N'
        ],
        //endregion

        //region Настройка для инпута
        [
            'isYouText',
            'text',
            'Текст',
            [
                "text",
                20
            ],
            'Y',
            '',
            'Y'
        ],
        //endregion

        //region Настройка для пароля
        [
            'isYouPass',
            'password',
            'Пароль',
            [
                "password",
                10,
                'noautocomplete'=>'Y'
            ],
            'N',
            'пароль',
            'N'
        ],//endregion

        //region Настройка многострочного текста
        [
            'isYouTextarea',
            'textarea',
            'Текстареа',
            [
                "textarea",
                5,
                10
            ],
            'N',
            'что как',
            'N'
        ],
        //endregion

        //region Настройка для выпадающего списка
        [
            'isYouSelectbox',
            'selectbox',
            'ko',
            [
                "selectbox",
                [
                    'lo'=>'po',
                    'zo'=>'do',
                    'ko'=>'ho',
                    'vo'=>'no'

                ]
            ]
        ],
        //endregion

        //region Настройка для многострочного списка
        [
            'isYouMultiselectbox',
            'multiselectbox',
            'ko,lo',
            ["multiselectbox",
                [
                    'lo'=>'po',
                    'zo'=>'do',
                    'ko'=>'ho',
                    'vo'=>'no'
                ]
            ]
        ],
        //endregion

        //region Настройка для статичного текста
        [
            'isYouStatictext',
            'statictext',
            'Статичный текст',
            ["statictext"]
        ],
        //endregion

        //region Настройка для статичног html
        [
            'isYouStatichtml',
            'statichtml',
            "Статичный <i><b>HTML<b><i>",
            ["statichtml"]
        ],
        //endregion

        //region Настройка комментарий в желтом блоке
        [
            'note' => "Текст в желтом блоке"
        ],
        //endregion
    ]
];
*/
//endregion

//region получение значений опций
/*
$optionValue = Option::get(
    $module_id,
    'myCheckBox'
);
Debug::dump($optionValue, '$optionValue');

$realValue = Option::getRealValue(
    $module_id,
    'myCheckBox'
);
Debug::dump($realValue, '$realValue');

$getDefaults = Option::getDefaults(
    $module_id
);

Debug::dump($getDefaults, '$getDefaults');

$getForModule = Option::getForModule(
    $module_id
);

Debug::dump($getForModule);
//endregion

*/

//Получение списка инфоблоков [id => CODE]
$res = CIBlock::GetList(
    [],
    [
        //'TYPE'     =>'catalog',
        'SITE_ID' => 's1',
        'ACTIVE' => 'Y',
        'CNT_ACTIVE' => 'Y',
        //'!CODE'    =>'my_products'
    ], true
);
$emptyValue = Loc::getMessage('OFCODE_USERRATING_OPTIONS_EMPTY_SELECT');
$iBlocks = ['0' => $emptyValue];
while ($ar_res = $res->Fetch()) {
    if (empty($ar_res['CODE'])) continue;
    $iBlocks[(string)$ar_res['ID']] = $ar_res['CODE'];
}

//Получение списка пользователей [id => Имя Фамилия]
$resUsers = Bitrix\Main\UserTable::getList([
    'select' => ['ID', 'NAME', 'LAST_NAME'],
    'filter' => ['ACTIVE' => 'Y']
]);

$users = ['0' => $emptyValue];
while ($user = $resUsers->fetch()) {
    $users[(string)$user['ID']] = $user['NAME'] . ' ' . $user['LAST_NAME'];
}

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

$request = HttpApplication::getInstance()->getContext()->getRequest();
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
<?php

//endregion
//region получение значений опций
/**/
$optionValue = Option::get(
    $module_id,
    'isModuleActive'
);
Debug::dump($optionValue, 'isModuleActive $optionValue');

$realValue = Option::getRealValue(
    $module_id,
    'isModuleActive'
);
Debug::dump($realValue, 'isModuleActive $realValue');

$optionValue = Option::get(
    $module_id,
    'mainIblock'
);
Debug::dump($optionValue, 'mainIblock $optionValue');

$realValue = Option::getRealValue(
    $module_id,
    'mainIblock'
);
Debug::dump($realValue, 'mainIblock $realValue');

$optionValue = Option::get(
    $module_id,
    'users'
);
Debug::dump($optionValue, 'users $optionValue');

$realValue = Option::getRealValue(
    $module_id,
    'users'
);
Debug::dump($realValue, 'users $realValue');

$getDefaults = Option::getDefaults(
    $module_id
);

Debug::dump($getDefaults, '$getDefaults');
Debug::dump($users, '$users');
Debug::dump($iBlocks, '$iblocks');
//endregion
/**/
?>