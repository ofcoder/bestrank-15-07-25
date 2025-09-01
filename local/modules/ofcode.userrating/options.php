<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use \Bitrix\Main\HttpApplication;

GLOBAL $APPLICATION;
Loc::loadMessages(__FILE__);
$module_id = 'ofcode.userrating';


if (!Loader::includeModule($module_id))
{
    return;
}

//region Массив для вкладок
$tabs[] = array(
    'DIV' => 'general',
    'TAB' => Loc::getMessage('OFCODE_USERRATING_TAB_GENERAL_NAME'),
    'TITLE' => Loc::getMessage('OFCODE_USERRATING_TAB_GENERAL_TITLE')
);
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
$arOptions=[
    'general'=>[
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

//endregion

//region получение значений опций

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




//region Cохранение формы

$request = HttpApplication::getInstance()->getContext()->getRequest();
$isSave = $request->isPost() && !empty($request['save']);
$isApply = $request->isPost() && !empty($request['apply']);

if (check_bitrix_sessid()
    && $request->isPost()
    && ($isSave || $isApply)
)
{

    foreach ($arOptions as $option) {
        __AdmSettingsSaveOptions($module_id, $option);
    }
    if ($isSave)
    {
        LocalRedirect($APPLICATION->GetCurPageParam());
    }
}

//endregion


//region Конструктор формы

$tabControl = new CAdminTabControl('tabControl', $tabs);
$tabControl->Begin();
?>

<form method="POST"
      action="<?php echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($module_id) ?>&lang=<?= LANGUAGE_ID ?>" id="baseexchange_form">
    <?php

    foreach($arOptions as $option){
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

?>