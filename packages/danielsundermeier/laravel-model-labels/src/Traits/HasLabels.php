<?php

namespace D15r\ModelLabels\Traits;

use Illuminate\Support\Str;

trait HasLabels
{
    public static function label(int $count = 0, string $case = 'nominativ')
    {
        return ($count == 1 ? self::LABELS[$case]['singular'] : self::LABELS[$case]['plural']);
    }
}

?>