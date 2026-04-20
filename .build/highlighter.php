<?php

/**
 * Minimal PHP syntax highlighter - runs at build time, zero runtime cost
 * Uses PHP's built-in tokenizer for accurate highlighting
 */
function highlightCode(string $code, string $language = 'php'): string
{
    if ($language !== 'php') {
        return htmlspecialchars($code);
    }
    
    $tokens = token_get_all('<?php ' . $code);
    $output = '';
    
    foreach ($tokens as $token) {
        if (is_string($token)) {
            $output .= htmlspecialchars($token);
            continue;
        }
        
        [$id, $text] = $token;
        $text = htmlspecialchars($text);
        
        $class = match($id) {
            T_COMMENT, T_DOC_COMMENT => 'comment',
            T_CONSTANT_ENCAPSED_STRING, T_ENCAPSED_AND_WHITESPACE => 'string',
            T_VARIABLE => 'variable',
            T_FUNCTION, T_CLASS, T_INTERFACE, T_TRAIT, T_NAMESPACE, T_USE,
            T_PUBLIC, T_PRIVATE, T_PROTECTED, T_STATIC, T_FINAL, T_ABSTRACT,
            T_IF, T_ELSE, T_ELSEIF, T_FOREACH, T_FOR, T_WHILE, T_DO,
            T_SWITCH, T_CASE, T_BREAK, T_CONTINUE, T_RETURN, T_NEW,
            T_EXTENDS, T_IMPLEMENTS, T_CONST, T_ECHO, T_PRINT => 'keyword',
            T_LNUMBER, T_DNUMBER => 'number',
            default => null
        };
        
        $output .= $class ? "<span class=\"{$class}\">{$text}</span>" : $text;
    }
    
    // Remove the added <?php
    return preg_replace('/^<span class="keyword">&lt;\?php<\/span>\s*/', '', $output);
}
