<?php

namespace Viloveul\Database;

use InvalidArgumentException;
use Viloveul\Database\Contracts\Expression as IExpression;

class Expression implements IExpression
{
    /**
     * @var mixed
     */
    protected $left;

    /**
     * @var mixed
     */
    protected $op;

    /**
     * @var mixed
     */
    protected $right;

    /**
     * @param string $expression
     */
    public function __construct(string $expression)
    {
        if (preg_match('~(.+)\s?(beetwen|\=|\<\=?|\>\=?|like|not\sin|in)\s?(.+)~i', $expression, $match)) {
            $this->left = trim($match[1]);
            $this->op = strtoupper(trim($match[2]));
            $this->right = trim($match[3]);
        } else {
            throw new InvalidArgumentException('Expression cannot passed.');

        }
    }

    public function getCompiled(): string
    {
        return "{$this->left} {$this->op} {$this->right}";
    }
}
