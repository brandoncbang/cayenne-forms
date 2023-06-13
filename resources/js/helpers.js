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

export function displayEntryTitle(entry) {
    let keys = Object.keys(entry.data).map(key => key.toLowerCase());

    let candidates = [
        ...keys.filter(key => key === 'email'),
        ...keys.filter(key => key !== 'email' && key.endsWith('email')),
        ...keys.filter(key => key === 'subject'),
        ...keys.filter(key => key !== 'subject' && key.endsWith('subject')),
    ];

    return entry.data[candidates[0]] ?? '(Untitled)';
}

export function displayEntryContent(entry) {
    let keys = Object.keys(entry.data).map(key => key.toLowerCase());

    let candidates = [
        ...keys.filter(key => key === 'message'),
        ...keys.filter(key => key !== 'message' && key.endsWith('message')),
        ...keys.filter(key => key === 'description'),
        ...keys.filter(key => key !== 'description' && key.endsWith('description')),
    ];

    return entry.data[candidates[0]] ?? null;
}

export function entryFieldIsEmail(key, value) {
    return key.toLowerCase().endsWith('email') && typeof value === 'string' && value.includes('@');
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
