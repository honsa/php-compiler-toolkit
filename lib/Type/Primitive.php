<?php
declare(strict_types=1);

namespace PHPCompilerToolkit\Type;

use PHPCompilerToolkit\Context;
use PHPCompilerToolkit\TypeAbstract;

class Primitive extends TypeAbstract {
    
    public const T_VOID = 1;
    public const T_VOID_PTR = 2;
    public const T_BOOL = 3;
    public const T_CHAR = 4;
    public const T_SIGNED_CHAR = 5;
    public const T_UNSIGNED_CHAR = 6;
    public const T_SHORT = 7;
    public const T_UNSIGNED_SHORT = 8;
    public const T_INT = 9;
    public const T_UNSIGNED_INT = 10;
    public const T_LONG = 11;
    public const T_UNSIGNED_LONG = 12;
    public const T_LONG_LONG = 13;
    public const T_UNSIGNED_LONG_LONG = 14;
    public const T_FLOAT = 15;
    public const T_DOUBLE = 16;
    public const T_LONG_DOUBLE = 17;
    public const T_SIZE_T = 18;
    
    public const PRIMITIVE_TYPE_MAP_TO_C = [
        self::T_VOID => 'void',
        self::T_VOID_PTR => 'void*',
        self::T_BOOL => 'bool',
        self::T_CHAR => 'char',
        self::T_SIGNED_CHAR => 'signed char',
        self::T_UNSIGNED_CHAR => 'unsigned char',
        self::T_SHORT => 'short',
        self::T_UNSIGNED_SHORT => 'unsigned short',
        self::T_INT => 'int',
        self::T_UNSIGNED_INT  => 'unsigned int',
        self::T_LONG  => 'long',
        self::T_UNSIGNED_LONG  => 'unsigned long',
        self::T_LONG_LONG  => 'long long',
        self::T_UNSIGNED_LONG_LONG  => 'unsigned long long',
        self::T_FLOAT  => 'float',
        self::T_DOUBLE  => 'double',
        self::T_LONG_DOUBLE  => 'long double',
        self::T_SIZE_T  => 'size_t',
    ];

    public int $kind;

    public function __construct(Context $context, int $kind) {
        parent::__construct($context);
        $this->kind = $kind;
    }

    public function asCString(): string {
        if (isset(self::PRIMITIVE_TYPE_MAP_TO_C[$this->kind])) {
            return self::PRIMITIVE_TYPE_MAP_TO_C[$this->kind];
        }
        throw new \LogicException("Unknown kind to c type");
    }

    public function isVoid(): bool {
        return $this->kind === self::T_VOID;
    }

    public function isSigned(): bool {
        switch ($this->kind) {
            case self::T_VOID_PTR:
            case self::T_UNSIGNED_CHAR:
            case self::T_UNSIGNED_SHORT:
            case self::T_UNSIGNED_INT:
            case self::T_UNSIGNED_LONG:
            case self::T_UNSIGNED_LONG_LONG:
                return false;
        }
        return true;
    }

    public function isFloatingPoint(): bool {
        switch ($this->kind) {
            case self::T_FLOAT:
            case self::T_DOUBLE:
            case self::T_LONG_DOUBLE:
                return true;
        }
        return false;
    }
}