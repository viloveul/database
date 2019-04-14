<?php

namespace Viloveul\Database\Contracts;

interface RelationShip
{
    const BELONGS_TO = 11;

    const BELONGS_TO_THROUGH = 12;

    const HAS_MANY = 13;

    const HAS_MANY_THROUGH = 14;

    const HAS_ONE = 15;
}
