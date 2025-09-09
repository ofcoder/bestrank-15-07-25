<?php


namespace Ofcode\UserRating\Helpers;


use Bitrix\Main\Application,
    Bitrix\Main\Config\Option;


class Options
{
    const moduleId = 'ofcode.userrating';

    public static function getModuleId()
    {
        return self::moduleId;
    }

    /**
     * Получить путь к модулю
     * @param bool $absolute
     * @return string
     */
    public static function getModuleDir($absolute = false)
    {
        if ($absolute)
            return str_replace('lib/Helpers', "", __DIR__);

        return str_replace([Application::getDocumentRoot(), 'lib/Helpers'], "", __DIR__);
    }

    public static function getParam($code, $default = null)
    {
        return trim(Option::get(self::getModuleId(), $code, $default));
    }

    public static function setParam($code, $value, $default = null)
    {
        Option::set(self::getModuleId(), $code, $value, $default);
    }


}