<?php

use Illuminate\Support\Str;

/**
 * Escape a string, so it can be displayed inside Markdown content, such as a Markdown email template.
 */
function escapeMarkdown(string $markdown): string
{
    $searches = [
        '\\', '!', '"', '#', '$', '%', '&', "'", '(', ')', '*', '+', ',', '-', '.', '/', ':', ';',
        '<', '=', '>', '?', '@', '[', ']', '^', '_', '`', '{', '|', '}', '~',
    ];

    $replacements = [
        '\\\\', '\!', '\"', '\#', '\$', '\%', '\&', "'", '\(', '\)', '\*', '\+', '\,', '\-', '\.', '\/', '\:', '\;',
        '\<', '\=', '\>', '\?', '\@', '\[', '\]', '\^', '\_', '\`', '\{', '\|', '\}', '\~',
    ];

    return Str::replace($searches, $replacements, $markdown);
}
