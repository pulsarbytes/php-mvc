<?php
namespace Core;

use App\Config;

/**
 * Error and exception handler.
 */
class Error
{
    /**
     * Converts all errors to exceptions by throwing an ErrorException.
     *
     * @param int $level Error level
     * @param string $message Error message
     * @param string $file FIlename the error was raised in
     * @param string $line Line number in the file
     *
     * @return void
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0)
            throw new \ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Exception handler.
     *
     * @param Exception $exception The exception
     */
    public static function exceptionHandler($exception)
    {
        $code = $exception->getCode();

        if ($code != 404)
            $code = 500;

        http_response_code($code);

        if (Config::SHOW_ERRORS)
        {
            echo "<h1>Error $code</h1>";
            echo "<p>Uncaught exception: '".get_class($exception)."'</p>";
            echo '<p>Message: '.$exception->getMessage().'</p>';
            echo '<p>Stack trace:<pre>'.$exception->getTraceAsString().'</pre></p>';
            echo '<p>Thrown in '.$exception->getFile().' on line '.$exception->getLine().'</p>';
        }
        else
        {
            $log = dirname(__DIR__) .'/logs/'.date('Y-m-d').'.txt';
            ini_set('error_log', $log);

            $message = "Uncaught exception: '".get_class($exception)."'";
            $message .= "\nMessage: ".$exception->getMessage();
            $message .= "\nStack trace: ".$exception->getTraceAsString();
            $message .= "\nThrown in ".$exception->getFile()." on line ".$exception->getLine();

            error_log($message);

            View::renderTemplate("$code.html");
        }
    }
}