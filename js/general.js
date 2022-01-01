function createHTMLTable(json, columnNames) {
    columns = columnNames.replace(/\s/g, '').split(',');
    var table = document.createElement('table');
    table.className = 'table';

    // Intestazioni
    var headers = document.createElement('thead');
    var headerRow = document.createElement('tr');
    columns.forEach(column => {
        var header = document.createElement('th');
        header.innerHTML = column;
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