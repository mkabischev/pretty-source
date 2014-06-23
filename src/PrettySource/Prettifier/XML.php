<?php

namespace PrettySource\Prettifier;

class XML implements PrettifierInterface
{

    /**
     * Returns supported format
     *
     * @return string
     */
    public function getFormat()
    {
        return 'xml';
    }

    /**
     * Detects if input matches to current format
     *
     * @param string $input
     * @return boolean
     */
    public function match($input)
    {
        return $input[0] == '<';
    }

    /**
     * Prettifies source string
     *
     * @param string $input
     * @throws Exception
     * @return string
     */
    public function prettify($input)
    {
        $prevInternalErrorValue = libxml_use_internal_errors(true);
        $replaces = 0;
        try {
            $xml_obj = new \SimpleXMLElement($input);
            $level = 4;
            $indent = 0; // current indentation level
            $pretty = array();

            // get an array containing each XML element
            $input = explode("\n", preg_replace('/>\s*</', ">\n<", $xml_obj->asXML()));

            // shift off opening XML tag if present
            if (count($input) && preg_match('/^<\?\s*xml/', $input[0])) {
                $pretty[] = array_shift($input);
            }

            foreach ($input as $el) {
                if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {
                    // opening tag, increase indent
                    $pretty[] = str_repeat(' ', $indent) . $el;
                    $indent += $level;
                } else {
                    if (preg_match('/^<\/.+>$/', $el)) {
                        $indent -= $level; // closing tag, decrease indent
                    }
                    if ($indent < 0) {
                        $indent += $level;
                    }
//                var_dump( $el );
                    $pretty[] = str_repeat(' ', $indent) . $el;
                }
            }
        } catch( \Exception $e ) {
            /** @var \LibXMLError[] $errors */
            $errors = libxml_get_errors();
            $errorMessage = $e->getMessage();
            foreach( $errors as $error ) {
                $errorMessage .= "\n" . $error->message;
            }
            libxml_use_internal_errors( $prevInternalErrorValue );
            throw new Exception( $errorMessage );
        }
        return implode("\n", $pretty);
    }

}