var listContainer;
var paginationElement;
var currentPage = 1, rows = 3;

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

function displayList(records, container) {
    container.innerHTML = '';
    records.forEach(record => {
        let card = createCard(record);
        container.appendChild(card);
    });
}

function createCardBodyElement(info) {
    let wrapper = document.createElement('p');
    wrapper.append(info);
    return wrapper;
}

function displayPageButtons(totalPages, container, callback) {
    container.innerHTML = '';

    // previous page btn
    let previousPageBtn = document.createElement('button');
    previousPageBtn.innerHTML = '<';
    previousPageBtn.onclick = function () {
        goToPage(callback, currentPage - 1);
    }
    if (currentPage == 1) {
        previousPageBtn.disabled = 'true';
    }

    // next page btn
    let nextPageBtn = document.createElement('button');
    nextPageBtn.innerHTML = '>';
    nextPageBtn.onclick = function () {
        goToPage(callback, currentPage + 1);
    }
    if (currentPage >= totalPages) {
        nextPageBtn.disabled = 'true';
    }
    
    // input page
    let pageInput = document.createElement('input');
    pageInput.type = 'number';
    pageInput.value = currentPage;
    let totalPageSpan = document.createElement('span');
    totalPageSpan.innerHTML = " / " + totalPages;
    let searchButton = document.createElement('button');
    searchButton.innerHTML = 'VAI';
    searchButton.onclick = function () {
        let page = Math.abs(parseInt(pageInput.value));
        goToPage(callback, page > totalPages ? 1 : page);
    }

    container.appendChild(previousPageBtn);
    container.appendChild(nextPageBtn);
    container.appendChild(pageInput);
    container.appendChild(totalPageSpan);
    container.appendChild(searchButton);
    
}

function goToPage(callback, page = 1, value = '%') {
    callback(page, rows, value).then(records => {

        if (page != 1 && !records[1].length)
            return goToPage(1, value);
            
        currentPage = page;

        displayList(records[1], listContainer);
        let totalPages = Math.ceil(records[0] / rows);
        displayPageButtons(totalPages, paginationElement, callback);    
    });
}