<?php

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 * (c) 2009 Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a block call node.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Twig_Node_Expression_BlockReference extends Twig_Node_Expression
{
    public function __construct(Twig_NodeInterface $name, $lineno, $tag = null)
    {
        if (is_bool($lineno)) {
            @trigger_error(sprintf('The %s method "$asString" argument is deprecated since version 1.28 and will be removed in 2.0.', __METHOD__), E_USER_DEPRECATED);

            $lineno = $tag;
            $tag = func_num_args() > 3 ? func_get_arg(3) : null;
        }

        parent::__construct(array('name' => $name), array('is_defined_test' => false, 'output' => false), $lineno, $tag);
    }

    public function compile(Twig_Compiler $compiler)
    {
        if ($this->getAttribute('is_defined_test')) {
            $compiler
                ->raw('$this->blockExists(')
                ->subcompile($this->getNode('name'))
                ->raw(', $context, $blocks)')
            ;
        } else {
            if ($this->getAttribute('output')) {
                $compiler
                    ->addDebugInfo($this)
                    ->write('$this->displayBlock(')
                    ->subcompile($this->getNode('name'))
                    ->raw(", \$context, \$blocks);\n")
                ;
            } else {
                $compiler
                    ->raw('$this->renderBlock(')
                    ->subcompile($this->getNode('name'))
                    ->raw(', $context, $blocks)')
                ;
            }
        }
    }
}
