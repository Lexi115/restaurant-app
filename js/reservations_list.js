var listContainer;
var paginationElement;
var currentPage = 1, rows = 1;

function displayList(records, container) {
    container.innerHTML = '';
    records.forEach(record => {
        let card = createCard(record);
        container.appendChild(card);
    });
}

function createCard(record) {
    let card = document.createElement('div');
    card.classList.add('card');
    card.id = record['cod_prenotazione'];

    // Intestazione
    let cardHeader = document.createElement('h2');
    cardHeader.innerHTML = '-V- ' + record['cod_prenotazione'];

    // Corpo con informazioni
    let cardBody = document.createElement('p');
    cardBody.appendChild(createCardBodyElement(record['descrizione_status']));
    cardBody.appendChild(createCardBodyElement(record['cognome'] + ' ' + record['nome']));
    cardBody.appendChild(createCardBodyElement(record['data']));
    cardBody.appendChild(createCardBodyElement('Numero persone: ' + record['n_persone']));

    card.appendChild(cardHeader);
    card.appendChild(cardBody);
    return card;
}

function createCardBodyElement(info) {
    let wrapper = document.createElement('p');
    wrapper.append(info);
    return wrapper;
}

function displayPageButtons(totalPages, container) {
    container.innerHTML = '';

    // previous page btn
    let previousPageBtn = document.createElement('button');
    previousPageBtn.innerHTML = '<';
    previousPageBtn.onclick = function () {
        goToPage(currentPage - 1);
    }
    if (currentPage == 1) {
        previousPageBtn.disabled = 'true';
    }

    // next page btn
    let nextPageBtn = document.createElement('button');
    nextPageBtn.innerHTML = '>';
    nextPageBtn.onclick = function () {
        goToPage(currentPage + 1);
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
        let page = parseInt(pageInput.value);
        goToPage(page > totalPages ? 1 : page);
    }

    container.appendChild(previousPageBtn);
    container.appendChild(nextPageBtn);
    container.appendChild(pageInput);
    container.appendChild(totalPageSpan);
    container.appendChild(searchButton);
    
}


function goToPage(page = 1, status = 1) {
    getReservations(page, rows, status).then(records => {
        currentPage = page;

        displayList(records[1], listContainer);
        let totalPages = Math.ceil(records[0] / rows);
        displayPageButtons(totalPages, paginationElement);
        
    });
}




async function getReservations(page, rows, status = '%') {
    var params = 'q=reservations&page=' + page + '&rows=' + rows + '&status=' + status;
    const response = await fetch('../api/api_get.php?' + params);
    return await response.json();
}

window.onload = function () {
    listContainer = document.querySelector('#list-container');
    paginationElement = document.querySelector('#pagination-element');

    goToPage();
}