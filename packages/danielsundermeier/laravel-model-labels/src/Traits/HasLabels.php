<?php

namespace D15r\ModelLabels\Traits;

use Illuminate\Support\Str;

trait HasLabels
{
    public function initializeHasLabels() : void
    {
        $this->append([
            'label_singular',
            'label_plural',
        ]);
    }

    public static function label(int $count = 0, string $case = 'nominativ')
    {
        return ($count == 1 ? self::labels()[$case]['singular'] : self::labels()[$case]['plural']);
    }

    protected static function labels(string $case) : array
    {
        return [
            'nominativ' => [
                'singular' => 'Einzahl',
                'plural' => 'Mehrzahl',
            ],
        ];
    }

    public function getLabelSingularAttribute() : string
    {
        return self::label(1);
    }

    public function getLabelPluralAttribute() : string
    {
        return self::label();
    }
}

?>