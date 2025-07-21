<?php

if(!function_exists('libsodiumEncrypt'))
{
    /**
       * @param  string $str
       *
       * @return mixed
       *
       */
	function libsodiumEncrypt($str)
    {
        try {
            $message = trim($str);
            if ($message != '') {
                $nonce_decoded = sodium_hex2bin(LIBSODIUM_NONCE);
                $key_decoded = sodium_hex2bin(LIBSODIUM_KEY);
                // encrypt message and combine with nonce
                $cipher = sodium_bin2hex(sodium_crypto_secretbox($message, $nonce_decoded, $key_decoded));
                // cleanup
                sodium_memzero($message);
                sodium_memzero($key_decoded);
                sodium_memzero($nonce_decoded);
                //return utf8_decode(utf8_encode(rtrim($cipher)));
                return mb_convert_encoding(mb_convert_encoding(rtrim($cipher),'UTF-8', 'ISO-8859-1'),'ISO-8859-1', 'UTF-8'); 
                //return sodium_bin2hex($cipher);
            } else {
                return "";
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}

if(!function_exists('libsodiumDecrypt'))
{
    /**
       * @param  string $str
       *
       * @return mixed
       *
       */
	function libsodiumDecrypt($str)
    {
        try {
            $encrypted = trim($str);
            if (!empty($encrypted)) {
                if(ctype_xdigit($encrypted)){
                    $decoded = sodium_hex2bin($encrypted);
                    /** @phpstan-ignore-next-line */
                    if ($decoded === false) {
                        return '';
                    }
                    $nonce_decoded = sodium_hex2bin(LIBSODIUM_NONCE);
                    $key_decoded = sodium_hex2bin(LIBSODIUM_KEY);
                    // decrypt it
                    $message = sodium_crypto_secretbox_open($decoded, $nonce_decoded, $key_decoded);

                    if ($message === false) {
                        return '';
                    }
                    // cleanup
                    sodium_memzero($decoded);
                    sodium_memzero($key_decoded);
                    sodium_memzero($nonce_decoded);
                    //return utf8_decode(utf8_encode(rtrim($message)));
                    return mb_convert_encoding(mb_convert_encoding(rtrim($message),'UTF-8', 'ISO-8859-1'),'ISO-8859-1', 'UTF-8'); 
                } else {
                    return '';
                }
            } else {
                return '';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}

if(!function_exists('keygenerate'))
{
    /**
       *
       * @return mixed
       *
     */
    function keygenerate() 
    {
        $secretKey = sodium_crypto_secretbox_keygen();
        $secretKey = sodium_bin2hex($secretKey);
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $nonce = sodium_bin2hex($nonce);
        return ['key'=>$secretKey,'nonce'=>$nonce];
    }

}

?>