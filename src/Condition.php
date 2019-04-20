<?php

namespace Viloveul\Database;

use Viloveul\Database\Contracts\Compiler as ICompiler;
use Viloveul\Database\Contracts\Condition as ICondition;

abstract class Condition implements ICondition
{
    /**
     * @var mixed
     */
    protected $compiler;

    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * @param ICompiler $compiler
     */
    public function __construct(ICompiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @return mixed
     */
    public function all(): array
    {
        return $this->conditions;
    }

    public function clear(): void
    {
        foreach ($this->conditions as $key => $value) {
            $this->conditions[$key] = null;
            unset($this->conditions[$key]);
        }
        $this->conditions = [];
    }

    /**
     * @param array $condition
     */
    public function push(array $condition): void
    {
        $this->conditions[] = $condition;
    }
}
