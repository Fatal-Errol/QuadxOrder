<?php

namespace QuadxModule\Mvc;

class Helper {
    /**
     * Converts underscore and dash separated names to camel case
     * 
     * @param stribng $raw_string
     * @return string
     */
    public static function convertToCamelCase($raw_string)
    {
        $camel_cased_string = str_replace(
            ' ', 
            '', 
            ucwords(
                strtolower(
                    str_replace(
                        '-',
                        ' ', 
                        str_replace(
                            '_', 
                            ' ', 
                            $raw_string
                        )
                    )
                )
            )
        );
        
        return $camel_cased_string;
    }
}