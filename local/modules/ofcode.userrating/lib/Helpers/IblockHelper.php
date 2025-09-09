<?php
    
    namespace Ofcode\UserRating\Helpers;
    defined('B_PROLOG_INCLUDED') || die;
    use Bitrix\Main\Loader,
        Bitrix\Main\ModuleManager,
        Bitrix\Main\Diag\Debug,
        Bitrix\Main\Localization\Loc,
        Bitrix\Main\Config\Option,
        Bitrix\Main\HttpApplication;
    
    Loader::includeModule('iblock');
    
    class IblockHelper
    {
        public static function prepareParamsGetList($params): array
        {
            $prepareParams = [[], [], true];
            
            if (isset($params['order'])) {
                $prepareParams['order'] = $params['order'];
            }
            if (isset($params['filter'])) {
                $prepareParams['filter'] = $params['filter'];
            }
            if (isset($params['bIncCnt'])) {
                $prepareParams['bIncCnt'] = $params['bIncCnt'];
            }
            return $prepareParams;
        }
        
        public static function getList($params)
        {
            $params = self::prepareParamsGetList($params);
            return  \CIBlock::GetList(
                $params['order'],//Массив для сортировки результата. Содержит пары "поле сортировки"=>"направление сортировки"
                $params['filter'],
                $params['bIncCnt'] //true/false Возвращать ли количество элементов в информационном блоке в поле ELEMENT_CNT
            );
        }
        
        public static function iblockActiveGetList(): array
        {
            $params = [
                'filter' => [
                    'SITE_ID' => 's1',
                    'ACTIVE' => 'Y',
                    'CNT_ACTIVE' => 'Y',//Если значение Y, то при подсчете элементов будут учитываться только активные элементы, при любом другом значении все элементы
                ],
            ];
            //Получение списка инфоблоков [id => CODE]
            $res = self::getList($params);
            $emptyValue = Loc::getMessage('OFCODE_USERRATING_OPTIONS_EMPTY_SELECT');
            $iBlocks = ['0' => $emptyValue];
            while ($ar_res = $res->Fetch()) {
                if (empty($ar_res['CODE'])) continue;
                $iBlocks[(string)$ar_res['ID']] = $ar_res['CODE'];
            }
            return $iBlocks;
        }
    }