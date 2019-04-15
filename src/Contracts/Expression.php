<?php

namespace Viloveul\Database\Contracts;

interface Expression
{
    public function getCompiled(): string;
}
