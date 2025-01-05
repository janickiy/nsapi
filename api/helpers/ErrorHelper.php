<?php

namespace api\helpers;

/**
 * Class ErrorHelper
 * @package api\helpers
 */
class ErrorHelper
{
    /**
     * Ошибки в эксепшен строкой класть
     * @param array $arrError
     * @return string
     */
    public static function convertToString($arrError)
    {
        $str = '';
        foreach ($arrError as $key => $error) {
            $str .= $key . ': ';

            if (is_array($error)) {
                $str .= implode(',', $error) . PHP_EOL;
            } else {
                $str .= $error;
            }
        }

        return $str;
    }
}
