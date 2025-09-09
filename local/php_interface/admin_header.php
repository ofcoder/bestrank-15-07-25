<?php
    /*
    if ($USER->IsAdmin()===true){ //проверяем что авторизовались не под админом
        //Bitrix\Main\Diag\Debug::dump($adminMenu->aGlobalMenu, 'aGlobalMenu');
        unset($adminMenu->aGlobalMenu["global_menu_marketing"],
            $adminMenu->aGlobalMenu["global_menu_store"],
            $adminMenu->aGlobalMenu["global_menu_services"],
            $adminMenu->aGlobalMenu["global_menu_marketplace"],
            $adminMenu->aGlobalMenu["global_menu_crm_site_master"],
            $adminMenu->aGlobalMenu["global_menu_users"]
            //$adminMenu->aGlobalMenu["global_menu_settings"] //можно расскомментировать если нужно полностью скрыть доступ к настройкам
        
        );
        
        foreach ($adminMenu->aGlobalMenu['global_menu_settings']['items'] as $k=>$menudel){ //тут скрываем конкретные настройки
            if($menudel['text'] != 'Пользователи'){
                unset($adminMenu->aGlobalMenu['global_menu_settings']['items'][$k]);
            }else{
                foreach ($adminMenu->aGlobalMenu['global_menu_settings']['items'][$k]['items'] as $kk=>$podmenudel){
                    if($podmenudel['url'] != 'user_admin.php?lang=ru'){  //Список пользователей
                        unset($adminMenu->aGlobalMenu['global_menu_settings']['items'][$k]['items'][$kk]);
                        
                    }
                }
            }
        }
        
        foreach ($adminMenu->aGlobalMenu['global_menu_content']['items'] as $k=>$menudel){
            if($menudel['module_id'] != 'iblock'){ // оставляем только инфоблоки
                unset($adminMenu->aGlobalMenu['global_menu_content']['items'][$k]);
            }
        }
    }
*/