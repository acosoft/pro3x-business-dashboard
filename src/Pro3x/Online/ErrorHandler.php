<?php

namespace Pro3x\Online;

class ErrorHandler
{
    public function __construct()
    {
        set_error_handler( array( __CLASS__, 'handleError' ));
    }

    static public function handleError( $errno, $errstr, $errfile,
                                        $errline, array $errcontext )
    {
        throw new \ErrorException( $errstr, 0, $errno, $errfile, $errline );
    }
}

?>
