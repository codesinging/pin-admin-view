<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

if (!function_exists('call_closure')) {
    /**
     * Call a user function given by the first parameter, and the second parameter serve as the user function's parameter.
     * If the closure function does not has a return or return null, then this function return the second parameter.
     *
     * @param Closure $closure
     * @param mixed $parameters
     *
     * @return mixed
     */
    function call_closure(Closure $closure, $parameters = null)
    {
        return call_user_func($closure, $parameters) ?? $parameters;
    }
}