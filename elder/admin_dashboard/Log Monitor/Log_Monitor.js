//  Log_Monitor_Main_Page   

//  Internet_Traffic   

// Log_Monitor   
function generateLogEntry(date, info, action, user) {
    const formattedDate = formatDate(date);
    return `<tr><td>${formattedDate}</td><td>[INFO] ${info}: ${action} (User: ${user})</td></tr>`;
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = padZero(date.getMonth() + 1);
    const day = padZero(date.getDate());
    const hours = padZero(date.getHours());
    const minutes = padZero(date.getMinutes());
    const seconds = padZero(date.getSeconds());
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

function padZero(num) {
    return (num < 10 ? '0' : '') + num;
}

const logTable = document.getElementById('Log_Table');

logEntries.forEach(entry => {
    const logRow = generateLogEntry(entry.date, entry.info, entry.action, entry.user);
    logTable.innerHTML += logRow;
});