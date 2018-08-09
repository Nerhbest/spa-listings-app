<?php

namespace App\Filters\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface FilterContract
{
    public function apply(Request $request): Builder;
    public function model() : string;
}