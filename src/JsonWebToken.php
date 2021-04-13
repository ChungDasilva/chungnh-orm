<?php

class JsonWebToken
{
    public static function decode($jwt, $key = null, $verify = true)
    {
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            throw new UnexpectedValueException("Wrong number of segments");
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;
        if (null === ($header = JsonWebToken::jsonDecode(JsonWebToken::urlsafeB64Decode($headb64)))) {
            throw new UnexpectedValueException("InValid segment encoding");
        }

        if (null === ($payload = JsonWebToken::jsonDecode(JsonWebToken::urlsafeB64Decode($bodyb64)))) {
            throw new UnexpectedValueException("InValid segment encoding");
        }

        $sig = JsonWebToken::urlsafeB64Decode($cryptob64);
        if ($verify) {
            if (empty($header->alg)) {
                throw new DomainException("empty algorithm");
            }
            if ($sig != JsonWebToken::sign("$headb64.$bodyb64", $key, $header->alg)) {
                throw new UnexpectedValueException("Signature verification failed");
            }
        }

        return $payload;
    }

    public static function encode($payload, $key, $algo = 'HS256')
    {
        $header = array('typ' => 'JsonWebToken', 'alg' => $algo);
        $segments = array();
        $segments[] = JsonWebToken::urlsafeB64Encode(JsonWebToken::jsonEncode($header));
        $segments[] = JsonWebToken::urlsafeB64Encode(JsonWebToken::jsonEncode($payload));
        $signing_input = implode('.', $segments);
        $signature = JsonWebToken::sign($signing_input, $key, $algo);
        $segments[] = JsonWebToken::urlsafeB64Encode($signature);
        return implode('.', $segments);
    }

    public static function sign($msg, $key, $method = 'HS256')
    {
        $methods = array(
            'HS256' => 'sha256',
            'HS384' => 'sha384',
            'HS512' => 'sha512',
        );
        if (empty($methods[$method])) {
            throw new DomainException("Algorithm not supported");
        }

        return hash_hmac($methods[$method], $msg, $key, true);
    }

    public static function jsonDecode($input)
    {
        $obj = json_decode($input);
        if (function_exists('json_last_error') && $errno = json_last_error()) {
            JsonWebToken::_handleJsonError($errno);
        } elseif ($obj === 'null' && $input !== null) {
            throw new DomainException("Null result with non-null input");
        }

        return $obj;
    }

    public static function jsonEncode($input)
    {
        $json = json_encode($input);
        if (function_exists('json_last_error()') && $errno = json_last_error()) {
            JsonWebToken::_handleJsonError($errno);
        } elseif ($json === 'null' && $input !== null) {
            throw new DomainException("Null result with non-null input");
        }
        return $json;
    }

    public static function urlsafeB64Decode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 -$remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public static function urlsafeB64Encode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    public static function _handleJsonError($errno)
    {
        $messages = array(
            JSON_ERROR_DEPTH => 'maximum stack depth exceeded',
            JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
            JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON'
        );
        throw new DomainException(
            isset($messages[$errno])
            ? $messages[$errno]
            : 'Unknow JSON error: ' . $errno
        );
    }
}

class JsonHelper
{
    public static function getJson($key, $value)
    {
        $out = array($key => $value);
        return json_encode($out);
    }
}