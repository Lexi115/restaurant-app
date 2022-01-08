var listContainer;
var paginationElement;
var currentPage = 1, rows = 3;

// Inserisci i dati dell'array JSON in una tabella (funzione sostituita da sistema a schede)
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

// Stampa lista con tutti i records passati
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

/**
 * Stampa elementi di paginazione (pulsanti per cambiare pagina,
 * input field per mostrare una pagina specifica)
 */
function displayPageButtons(totalPages, container, callback) {
    container.innerHTML = '';

    // Pulsante pagina precedente
    let previousPageBtn = document.createElement('button');
    previousPageBtn.innerHTML = '<';
    previousPageBtn.onclick = function () {
        goToPage(callback, currentPage - 1);
    }
    if (currentPage == 1) {
        previousPageBtn.disabled = 'true';
    }

    // Pulsante pagina successiva
    let nextPageBtn = document.createElement('button');
    nextPageBtn.innerHTML = '>';
    nextPageBtn.onclick = function () {
        goToPage(callback, currentPage + 1);
    }
    if (currentPage >= totalPages) {
        nextPageBtn.disabled = 'true';
    }
    
    // Input field e pulsante di ricerca
    let pageInput = document.createElement('input');
    pageInput.type = 'number';
    pageInput.value = currentPage;
    let totalPageSpan = document.createElement('span');
    totalPageSpan.innerHTML = " / " + totalPages;
    let searchButton = document.createElement('button');
    searchButton.innerHTML = 'VAI';
    searchButton.onclick = function () {
        let page = parseInt(pageInput.value);
        if (page <= 0 || page > totalPages) page = 1;
        goToPage(callback, page);
    }

    container.appendChild(previousPageBtn);
    container.appendChild(nextPageBtn);
    container.appendChild(pageInput);
    container.appendChild(totalPageSpan);
    container.appendChild(searchButton);
}

// Cambia pagina
function goToPage(callback, page = 1, value = '%') {
    callback(page, rows, value).then(records => {

        // Numero totale dei records (tutte le pagine comprese)
        totalRecords = records[0];

        // Records di quella pagina
        pageRecords = records[1];

        if (page != 1 && !pageRecords.length)
            return goToPage(1, value);
            
        currentPage = page;

        displayList(pageRecords, listContainer);
        let totalPages = Math.ceil(totalRecords / rows);
        displayPageButtons(totalPages, paginationElement, callback);    

    });
}
