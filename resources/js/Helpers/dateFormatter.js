export function formatDate(timestamp) {
    if (!timestamp) return;
    const date = new Date(timestamp);
    const options = {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hour12: false // Use 24-hour format
    };
    return date.toLocaleDateString(undefined, options); // Adjust the format as needed
}
