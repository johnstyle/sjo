<?php

namespace PHPTools\Helpers;

function __($original)
{
    $text = PHPTools\Libraries\I18n::gettext($original);

    return PHPTools\Libraries\I18n::replace($text, func_get_args(), 1);
}

function n__($original, $plural, $value)
{
    $text = Translator::ngettext($original, $plural, $value);
    
    return PHPTools\Libraries\I18n::replace($text, func_get_args(), 3);
}

function p__($context, $original)
{
    $text = Translator::pgettext($context, $original);
    
    return PHPTools\Libraries\I18n::replace($text, func_get_args(), 2);
}
