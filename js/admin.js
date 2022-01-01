var columnNames = "username, nome_gruppo";

async function get_accounts(columns = '*', group = '%') {
    var params = 'q=accounts&group=' + group + '&columns=' + columns;
    const response = await fetch('api/api_get.php?' + params);
    return await response.json();
}

window.onload = function () {
    get_accounts(columnNames).then(accounts => {
        document.body.appendChild(createHTMLTable(accounts, columnNames));
    });
}