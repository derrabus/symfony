<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\ExpressionLanguage\Tests\Node;

use Symfony\Component\ExpressionLanguage\Node\ArgumentsNode;
use Symfony\Component\ExpressionLanguage\Node\ArrayNode;

class ArgumentsNodeTest extends ArrayNodeTest
{
    public function getCompileData(): iterable
    {
        return [
            ['"a", "b"', $this->getArrayNode()],
        ];
    }

    public function getDumpData(): iterable
    {
        return [
            ['"a", "b"', $this->getArrayNode()],
        ];
    }

    protected function createArrayNode(): ArrayNode
    {
        return new ArgumentsNode();
    }
}
