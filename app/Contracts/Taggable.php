<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

interface Taggable
{

    public function tags() : MorphToMany;

}

?>