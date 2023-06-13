export function displayNumber(n, cutoff = 9999) {
    return n > cutoff ? `${cutoff.toLocaleString()}+` : n.toLocaleString();
}

export function displayDate(date) {
    return (new Date(date)).toLocaleDateString();
}

export function displayDateTime(date) {
    return (new Date(date)).toLocaleString();
}

export function displayObjectKey(key) {
    return key
        .split(/[-_]/)
        .map(word => word[0].toLocaleUpperCase() + word.slice(1))
        .join(' ');
}

export function getFormEmbedCode(form) {
    return `
<form
    action="${route('forms.entries.store', { form })}"
    method="POST"
>
    <!-- ... -->
</form>
    `.trim();
}

export function getEntryTitle(entry) {
    if ('email' in entry.data) {
        return entry.data.email;
    }
    if ('subject' in entry.data) {
        return entry.data.subject;
    }

    return '(Untitled)';
}

export function getEntryContent(entry) {
    if ('message' in entry.data) {
        return entry.data.message;
    }
    if ('description' in entry.data) {
        return entry.data.description;
    }

    return null;
}
