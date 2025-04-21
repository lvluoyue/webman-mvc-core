<?php

namespace Luoyue\WebmanMvcCore\annotation\exception\parser;

use LinFly\Annotation\Contracts\IAnnotationParser;
use ReflectionClass;
use Throwable;

class ExceptionHandlerParser implements IAnnotationParser
{

    private static array $exceptions = [];

    private static ExceptionTreeNode $exceptionTree;

    public static function process(array $item): void
    {
        foreach ((array)$item['parameters']['exceptionClass'] as $exceptionClass) {
            $reflectionClass = new ReflectionClass($exceptionClass);
            if (!$reflectionClass->isSubclassOf(Throwable::class)) {
                throw new \InvalidArgumentException('Exception class must be a subclass of Throwable');
            }
            $reflectionMethod = new \ReflectionMethod($item['class'], $item['method']);
            $returnName = $reflectionMethod->getReturnType()?->getName();
            if($returnName !== \support\Response::class && $returnName !== \Webman\Http\Response::class) {
                throw new \InvalidArgumentException('Exception handler must return Response');
            }
            self::$exceptionTree ??= new ExceptionTreeNode(Throwable::class);
            self::addException($reflectionClass);
            self::$exceptions[$exceptionClass] = [
                $item['class'],
                $item['method'],
                $item['parameters']['app'],
                $item['parameters']['logChannel'],
            ];
        }
    }

    private static function addException(?ReflectionClass $reflectionClass, ?ExceptionTreeNode $parentNode = null)
    {
        if (!$reflectionClass) {
            return;
        }

        $nodeName = $reflectionClass->getName();
        $node = new ExceptionTreeNode($nodeName);

        if ($parentNode) {
            $parentNode->addChild($node);
        } else {
            // Find the correct parent node in the existing tree
            $parentNode = self::findParentNode(self::$exceptionTree, $reflectionClass->getParentClass()?:null);
            if ($parentNode) {
                $parentNode->addChild($node);
            } else {
                // If no parent node is found, add to the root
                self::$exceptionTree->addChild($node);
            }
        }

        self::addException($reflectionClass->getParentClass() ?: null, $node);
    }

    private static function findParentNode(?ExceptionTreeNode $currentNode, ?ReflectionClass $parentReflectionClass): ?ExceptionTreeNode
    {
        if (!$currentNode || !$parentReflectionClass) {
            return null;
        }

        if ($currentNode->name === $parentReflectionClass->getName()) {
            return $currentNode;
        }

        foreach ($currentNode->children as $child) {
            $result = self::findParentNode($child, $parentReflectionClass);
            if ($result) {
                return $result;
            }
        }

        return null;
    }


    private static function flattenTree(?ExceptionTreeNode $node, array &$flattenedExceptions): void
    {
        if (!$node) {
            return;
        }

        $flattenedExceptions[] = $node->name;
        foreach ($node->children as $child) {
            self::flattenTree($child, $flattenedExceptions);
        }
    }

    public static function getExceptions(): array
    {
        // Flatten the tree and sort by inheritance depth
        $flattenedExceptions = [];
        self::flattenTree(self::$exceptionTree, $flattenedExceptions);

        // Sort by depth in descending order (most specific first)
        usort($flattenedExceptions, function ($a, $b) {
            return count(class_parents($b)) - count(class_parents($a));
        });

        $sortedExceptions = [];
        foreach ($flattenedExceptions as $exceptionClass) {
            if (isset(self::$exceptions[$exceptionClass])) {
                $sortedExceptions[$exceptionClass] = self::$exceptions[$exceptionClass];
            }
        }

        return $sortedExceptions;
    }

}