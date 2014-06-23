<?php

namespace PrettySource\Prettifier;

use Seld\JsonLint\JsonParser;

class Json implements PrettifierInterface
{

    /**
     * Returns supported format
     *
     * @return string
     */
    public function getFormat()
    {
        return 'json';
    }


    /**
     * Detects if input matches to current format
     *
     * @param string $input
     * @return boolean
     */
    public function match($input)
    {
        return $input[0] == '{';
    }


    /**
     * Prettifies source string
     *
     * @link https://github.com/ryanuber/projects/blob/master/PHP/JSON/jsonpp.php
     * @param string $input
     * @return string
     */
    public function prettify($input)
    {
        $parser = new JsonParser();
        if ( $err = $parser->lint( $input ) ) {
            throw new Exception($err->getMessage());
        }
        $result = '';
        $istr = '  ';
        for ($p = $q = $i = 0; isset($input[$p]); $p++) {
            $input[$p] == '"' && ($p > 0 ? $input[$p - 1] : '') != '\\' && $q = !$q;
            if (!$q && strchr(" \t\n", $input[$p])) {
                continue;
            }
            if (strchr('}]', $input[$p]) && !$q && $i--) {
                strchr('{[', $input[$p - 1]) || $result .= "\n" . str_repeat($istr, $i);
            }
            $result .= $input[$p];
            if (strchr(',{[', $input[$p]) && !$q) {
                $i += strchr('{[', $input[$p]) === FALSE ? 0 : 1;
                strchr('}]', $input[$p + 1]) || $result .= "\n" . str_repeat($istr, $i);
            }
        }
        return $result;
    }

}