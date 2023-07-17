<?php

use Illuminate\Support\Str;

/**
 * Escape a string, so it can be displayed inside Markdown content, such as a Markdown email template.
 */
function escapeMarkdown(?string $markdown): string
{
    if (is_null($markdown)) {
        return '';
    }

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
