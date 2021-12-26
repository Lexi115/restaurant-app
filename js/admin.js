var columnNames = "username, nome_gruppo";
var formattedColumnNames = {
    'username':'Username',
    'gruppo':'Gruppo',
    'nome_gruppo':'Nome Gruppo',
    'token_accesso':'Token'
}

async function get_accounts(columns = '*', group = '%') {
    var params = 'group=' + group + '&columns=' + columns;
    const response = await fetch('api/api_get_accounts.php?' + params);
    return await response.json();
}

function createHTMLTable(json, columnNames) {
    columns = columnNames.replace(/\s/g, '').split(',');
    var table = document.createElement('table');
    table.className = 'table';

    // Intestazioni
    var headers = document.createElement('thead');
    var headerRow = document.createElement('tr');
    columns.forEach(column => {
        var header = document.createElement('th');
        header.innerHTML = formattedColumnNames[column];
        headerRow.appendChild(header);
    });

    headers.append(headerRow);
    table.appendChild(headers);

    // Corpo
    var body = document.createElement('tbody');  
    json.forEach(object => {
        var elementRow = document.createElement('tr');

        columns.forEach(column => {
            var elementData = document.createElement('td');
            elementData.innerHTML = object[column];
            elementRow.appendChild(elementData);
        });

        body.append(elementRow);
    });

    table.appendChild(body);
    return table;
}

window.onload = function () {
    get_accounts(columnNames).then(accounts => {
        document.body.appendChild(createHTMLTable(accounts, columnNames));
    });
}