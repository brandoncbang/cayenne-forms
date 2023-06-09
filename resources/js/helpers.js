export function displayNumber(n, cutoff = 9999) {
    return n > cutoff ? `${cutoff.toLocaleString()}+` : n.toLocaleString();
}

export function displayDate(date) {
    return (new Date(date)).toLocaleDateString();
}

export function displayDateTime(date) {
    return (new Date(date)).toLocaleString();
}
