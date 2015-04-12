<?php

class __foowd_templates__40bcf1704994c6aaaf3b6c0b2e4b818d extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<ul><li>';
        $value = $this->resolveValue($context->find('name'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '</li><li>';
        $value = $this->resolveValue($context->find('price'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '</li><li>';
        $value = $this->resolveValue($context->find('thumb'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '</li><li>';
        $value = $this->resolveValue($context->find('description'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');
        $buffer .= '</li></ul>';

        return $buffer;
    }
}
