<?php

declare(strict_types=1);

use Core\Config;
use Core\Env;
use Core\Logger;

if (! function_exists('trimWhiteS')) {

    function trimWhiteS(string $text): string
    {
        return trim(preg_replace('/[\t\n\r\s]+/', ' ', $text));
    }
}

if (! function_exists('trimQuery')) {

    function trimQuery(string $query): string
    {
        $query = trimWhiteS($query);
        $query .= ';';
        return $query;
    }
}

if (! function_exists('fill')) {
    /**
     * @param  string $value
     * @param string|mixed[] $needle
     */
    function fill($value, string $template = ':?', $needle = '?'): string
    {
        $result = $template;
        if (is_array($value)) {
            foreach ($value as $i => $v) {
                $result = str_replace($needle[$i], (string) $v, $result);
            }
        } else {
            $result = str_replace($needle, $value, $template);
        }
        return $result;
    }
}

if (! function_exists('genPlaceHolders')) {

    function genPlaceHolders(array $arr, string $seperator = ', ', string $template = ':?'): string
    {
        $i = 0;
        $str = '';
        foreach ($arr as $v) {
            if ($i === 0) {
                $str .= fill($v, $template);
                $i++;
                continue;
            }
            $str .= $seperator . fill($v, $template);
            $i++;
        }
        return $str;
    }
}

if (! function_exists('placeHolderSeq')) {

    function placeHolderSeq(
        int $range,
        string $attr,
        string $seperator = ' or ',
        string $template = '? = :?#'
    ): array {
        $str = '';
        $rangeArr = range(0, $range - 1);
        $keys = [];
        foreach ($rangeArr as $i => $v) {
            if ($i === 0) {
                $str .= fill([$attr, $v], $template, ['?', '#']);
                $keys[] = "{$attr}{$v}";
                continue;
            }
            $str .= $seperator . fill([$attr, $v], $template, ['?', '#']);
            $keys[] = "{$attr}{$v}";
        }
        return [$str, $keys];
    }
}

if (! function_exists('send')) {

    function send(int $code = 200, array $body = null): void
    {
        http_response_code($code);
        if ($body) {
            echo json_encode($body);
        }
    }
}

if (! function_exists('sendError')) {
    /**
     * @param string|mixed[] $body
     */
    function sendError(int $code = 400, $body = null): void
    {
        $error = null;
        if ($body) {
            $error = [
                'error' => $body,
            ];
        }
        send($code, $error);
    }
}

if (! function_exists('env')) {

    function env(string $key, string $default = null): string
    {
        return Env::get($key, $default);
    }
}


if (! function_exists('config')) {

    /**
     * @return mixed
     */
    function config(string $key, string $default = null)
    {
        return Config::get($key, $default);
    }
}


if (! function_exists('log_write')) {

    /**
     * @param string|\Exception $message
     */
    function log_write($message): void
    {
        Logger::log($message);
    }
}
