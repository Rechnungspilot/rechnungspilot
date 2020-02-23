<?php

namespace App\Collections\CustomFields;

use App\Models\CustomFields\CustomField;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CustomFieldValueCollection extends Collection
{
    protected $request;

    public function validate(Request $request = null) : self
    {
        $this->request = $request ?? request();
        $this->map( function ($value)
        {
            $this->request->validate([
                $value->key => CustomField::getRule($value->customfield->input_type),
            ]);
        });

        return $this;
    }

    public function update(Request $request = null) : self
    {
        $this->request = $request ?? $this->request ?? request();
        $this->map( function ($value)
        {
            $value->update([
                'value' => $this->request->has($value->key) ? $this->request->input($value->key) : null,
            ]);
        });

        return $this;
    }
}

?>