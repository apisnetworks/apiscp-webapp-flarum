<?php declare(strict_types=1);
/*
 * Copyright (C) Apis Networks, Inc - All Rights Reserved.
 *
 * Unauthorized copying of this file, via any medium, is
 * strictly prohibited without consent. Any dissemination of
 * material herein is prohibited.
 *
 * For licensing inquiries email <licensing@apisnetworks.com>
 *
 * Written by Matt Saladna <matt@apisnetworks.com>, March 2024
 */

namespace Module\Support\Webapps\App\Type\Flarum;


use Module\Support\Php\TreeWalker;
use PhpParser\ConstExprEvaluationException;
use PhpParser\ConstExprEvaluator;
use PhpParser\Node;

/**
 * Class AST
 *
 * @package Module\Support\Webapps\App\Type\Flarum
 *
 */
class Walker extends \Module\Support\Php\TreeWalker
{

	public function replace(string $var, mixed $new): TreeWalker
	{
		return $this->walkReplace($var, $new, false);
	}

	public function set(string $var, mixed $new): TreeWalker
	{
		return $this->walkReplace($var, $new, true);
	}

	protected function walkReplace(string $var, mixed $new, bool $append = false): self
	{
		$replacement = $this->inferType($new);
		$node = $this->locateStorageNode();

	    $element = $this->findElementByKey($node, $var);
		if (null === $element) {
			if ($append) {
				$node->items[] = $this->buildDimNode($var, $replacement);
				dd((string)$this);
			}
		} else {
			$element->value = $replacement;
		}

		return $this;
	}

	private function buildDimNode(string $var, Node\Expr $val): ?Node
	{
		$components = explode('.', $var);
		$stack = new Node\Expr\ArrayItem(
			$val,
			$this->inferType(end($components))
		);

		while (false !== ($next = prev($components))) {
			$stack = new Node\Expr\ArrayItem(
				new Node\Expr\Array_([$stack]),
				$this->inferType($next)
			);
		}

		return $stack;
	}

	private function locateStorageNode(): Node
	{
		return $this->first(function (Node $stmt) {
			if (!$stmt instanceof Node\Stmt\Return_ || !$stmt->expr instanceof Node\Expr\Array_) {
				return false;
			}

			return true;
		})?->expr;
	}

	private function findElementByKey(Node\Expr\Array_ $node, string $var): ?Node\Expr\ArrayItem
	{
		$components = explode('.', $var);

		foreach ($node->items as $element) {
			if ($element->key->value === $var) {
				return $element;
			}
		}

		return null;
	}

	/**
	 * Supports dot-delimited nested values
	 *
	 * @param string     $var
	 * @param mixed|null $default
	 * @return mixed
	 */
	public function get(string $var, mixed $default = null): mixed
	{
		if (null === ($node = $this->findElementByKey($this->locateStorageNode(), $var))) {
			return $default;
		}

		try {
			return (new ConstExprEvaluator)->evaluateSilently($node->value);
		} catch (ConstExprEvaluationException $expr) {
			return (new \PhpParser\PrettyPrinter\Standard())->prettyPrint(
				[$node]
			);
		}
	}
}