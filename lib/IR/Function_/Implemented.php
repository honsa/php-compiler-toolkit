<?php
declare(strict_types=1);

namespace PHPCompilerToolkit\IR\Function_;
use PHPCompilerToolkit\IR\Block;
use PHPCompilerToolkit\IR\Function_;
use PHPCompilerToolkit\IR\Parameter;
use PHPCompilerToolkit\IR\Value;
use PHPCompilerToolkit\Context;
use PHPCompilerToolkit\Type;


abstract class Implemented implements Function_ {

    public Context $context;
    public Type $returnType;
    public array $parameters;
    public array $parametersByName = [];
    public string $name;
    public bool $isVariadic;
    public array $blocks = [];
    public array $locals = [];

    public function __construct(Context $context, string $name, Type $returnType, bool $isVariadic, Parameter ... $parameters) {
        $this->context = $context;
        $this->name = $name;
        $this->returnType = $returnType;
        $this->parameters = $parameters;
        foreach ($parameters as $parameter) {
            $this->parametersByName[$parameter->name] = $parameter;
        }
        $this->isVariadic = $isVariadic;
        $context->functions[] = $this;
    }

    public function createBlock(string $name): Block {
        $block = new Block($this, $name);
        if (empty($this->blocks)) {
            foreach ($this->parameters as $parameter) {
                $parameter->setValue(new Value\Value($block, $parameter->type));
            }
        }
        $this->blocks[] = $block;
        return $block;
    }

}