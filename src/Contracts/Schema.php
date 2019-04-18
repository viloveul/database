<?php

namespace Viloveul\Database\Contracts;

interface Schema
{
    const TYPE_BIGINT = 31;

    const TYPE_BINARY = 32;

    const TYPE_BLOB = 33;

    const TYPE_CHAR = 34;

    const TYPE_DATE = 35;

    const TYPE_DATETIME = 36;

    const TYPE_DECIMAL = 37;

    const TYPE_ENUM = 38;

    const TYPE_INT = 39;

    const TYPE_LONGBLOB = 40;

    const TYPE_LONGTEXT = 41;

    const TYPE_MEDBLOB = 42;

    const TYPE_MEDINT = 43;

    const TYPE_MEDTEXT = 44;

    const TYPE_SMALLBLOB = 45;

    const TYPE_SMALLINT = 46;

    const TYPE_SMALLTEXT = 47;

    const TYPE_TEXT = 48;

    const TYPE_TIME = 49;

    const TYPE_TIMESTAMP = 50;

    const TYPE_VARCHAR = 51;

    const TYPE_YEAR = 52;

    public function increment(): self;

    public function index(string $column): self;

    public function nullable(): self;

    public function primary(): self;

    public function run();

    public function set(string $name, int $type, $lenOrVals = null): self;

    public function unique(string $column): self;

    public function unsigned(): self;

    public function value($v): self;
}
