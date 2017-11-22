<?php

/**
 * @return GuzzleHttp\Client Guzzle http client
 */
function httpClient()
{
    return app('httpClient')->getClient();
}

/**
 * @return \App\Datmusic\Logger logger instance
 */
function logger()
{
    return app('logger');
}

/**
 * Decodes VK's audio mp3 url with their js code using nodejs.
 *
 * @param string $encoded encoded url
 * @param int    $userId  id of vk account
 *
 * @return string
 */
function decodeVkMp3Url($encoded, $userId)
{
    if (empty($encoded)) {
        logger()->log('Decoder.Empty');

        return '';
    }

    $nodejs = config('app.paths.nodejs');
    $js = config('app.paths.decode-js');

    return exec("{$nodejs} {$js} {$encoded} {$userId}");
}

/**
 * Function: sanitize (from Laravel)
 * Returns a sanitized string, typically for URLs.
 *
 * Parameters:
 *
 * @param $string - The string to sanitize.
 * @param $force_lowercase - Force the string to lowercase?
 * @param $anal - If set to *true*, will remove all non-alphanumeric characters.
 * @param $trunc - Number of characters to truncate to (default 100, 0 to disable).
 *
 * @return string sanitized string
 */
function sanitize($string, $force_lowercase = true, $anal = false, $trunc = 100)
{
    $strip = ['~', '`', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '=', '+', '[', '{', ']', '}', '\\', '|', ';', ':', '"', "'", '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8211;', '&#8212;', '—', '–', ',', '<', '>', '/', '?'];
    $clean = trim(str_replace($strip, '', strip_tags($string)));
    // $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal ? preg_replace('/[^a-zA-Z0-9]/', '', $clean) : $clean);
    $clean = ($trunc ? substr($clean, 0, $trunc) : $clean);

    return ($force_lowercase) ? (function_exists('mb_strtolower')) ? mb_strtolower($clean, 'UTF-8') : strtolower($clean) : $clean;
}

/**
 * Build full url. Prepends APP_URL to given string.
 *
 * @param $path
 *
 * @return string
 */
function fullUrl($path)
{
    return sprintf('%s/%s', env('APP_URL'), $path);
}

/**
 * Extracts integers from given string.
 *
 * @param $string
 *
 * @return mixed
 */
function getIntegers($string)
{
    preg_match_all('!\d+!', $string, $matches);

    return $matches[0][0];
}

/**
 * @return string random artist name
 */
function randomArtist()
{
    $randomArray = config('app.artists');
    $randomIndex = array_rand($randomArray);

    return $randomArray[$randomIndex];
}
