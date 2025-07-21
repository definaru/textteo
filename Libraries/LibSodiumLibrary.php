<?php
namespace App\Libraries;

class LibSodiumLibrary {

      /** @phpstan-ignore-next-line */
      private $nonce,  $LIB_SODIUM_NOUNCE;
      /** @phpstan-ignore-next-line */
      private $key, $LIB_SODIUM_KEY;
      /** @phpstan-ignore-next-line */
      private $block_size;

      public function __construct() {

          //$this->nonce = env('LIB_SODIUM_NOUNCE');
          //$this->key = env('LIB_SODIUM_KEY');
      }

    public function keygenerate() {
        $secretKey = sodium_crypto_secretbox_keygen();
        $secretKey = sodium_bin2hex($secretKey);
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $nonce = sodium_bin2hex($nonce);
        return ['key'=>$secretKey,'nonce'=>$nonce];
    }
       /**
       *
       *
       * @param  string $str
       *
       * @return mixed
       *
       */
      public static function encode($str) {
          try {
              $message = trim($str);
              if ($message != '') {
                  $nonce_decoded = sodium_hex2bin('ce2a98e557109fb1a1f536c2cc7c053874b6d8f41b538cd3');
                  $key_decoded = sodium_hex2bin('750b17218f30b619cdb42f646859fc037cbfa74ff7f0fabc5eb508185a90b594');
                  // encrypt message and combine with nonce
                  $cipher = sodium_bin2hex(sodium_crypto_secretbox($message, $nonce_decoded, $key_decoded));
                  // cleanup
                  sodium_memzero($message);
                  sodium_memzero($key_decoded);
                  sodium_memzero($nonce_decoded);
                  return utf8_decode(utf8_encode(rtrim($cipher)));
                  //return sodium_bin2hex($cipher);
              } else {
                  return "";
              }
          } catch (\Exception $e) {
              return $e->getMessage();
          }
      }

      /**
       *
       *
       * @param  string $code
       *
       * @return mixed
       *
       */
    public static function decode(string $code) {
          try {
              $encrypted = trim($code);
              if (!empty($encrypted)) {
                  if(ctype_xdigit($encrypted)){
                      $decoded = sodium_hex2bin($encrypted);
                      /** @phpstan-ignore-next-line */
                      if ($decoded === false) {
                          return '';
                      }
                      $nonce_decoded = sodium_hex2bin('ce2a98e557109fb1a1f536c2cc7c053874b6d8f41b538cd3');
                      $key_decoded = sodium_hex2bin('750b17218f30b619cdb42f646859fc037cbfa74ff7f0fabc5eb508185a90b594');
                      // decrypt it
                      $message = sodium_crypto_secretbox_open($decoded, $nonce_decoded, $key_decoded);

                      if ($message === false) {
                          return '';
                      }
                      // cleanup
                      sodium_memzero($decoded);
                      sodium_memzero($key_decoded);
                      sodium_memzero($nonce_decoded);
                      return utf8_decode(utf8_encode(rtrim($message)));
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

      /**
       *
       *
       * @param  string $hexdata
       *
       * @return mixed
       *
       */
      protected function hex2bin($hexdata) {
          $bindata = '';

          for ($i = 0; $i < strlen($hexdata); $i += 2) {
              $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
          }

          return $bindata;
      }
}
