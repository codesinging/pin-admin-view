<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdminView\Components;

use CodeSinging\PinAdminView\Foundation\Component;

class Button extends Component
{
    public function __construct($attributes = null, $content = null, bool $linebreak = null)
    {
        parent::__construct($attributes, $content, $linebreak);
    }
}