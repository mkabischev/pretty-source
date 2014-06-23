<?php

namespace PrettySource\Prettifier;

interface PrettifierInterface
{

    /**
     * Returns supported format
     *
     * @return string
     */
    public function getFormat();

    /**
     * Detects if input matches to current format
     *
     * @param string $input
     * @return boolean
     */
    public function match($input);

    /**
     * Prettifies source string
     *
     * @param string $input
     * @throws Exception
     * @return string
     */
    public function prettify($input);
}