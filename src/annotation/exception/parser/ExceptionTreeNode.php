<?php

namespace Luoyue\WebmanMvcCore\annotation\exception\parser;

class ExceptionTreeNode {

    public string $name;

    /** @var ExceptionTreeNode[] $children */
    public array $children = [];

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function addChild(ExceptionTreeNode $child) {
        $this->children[] = $child;
    }
}
