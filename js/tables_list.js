const editFormUrl = location.href + '/../edit_forms/edit_table.php';

function createCard(record) {
    let card = document.createElement('div');
    card.classList.add('card');
    card.id = record['numero_tavolo'];

    // Intestazione
    let cardHeader = document.createElement('h2');
    cardHeader.innerHTML = 'T. ' + card.id;

    // Corpo con informazioni
    let cardBody = document.createElement('p');
    cardBody.appendChild(createCardBodyElement(record['nome_sala']));
    cardBody.appendChild(createCardBodyElement('Numero posti: ' + record['n_posti']));

    card.appendChild(cardHeader);
    card.appendChild(cardBody);

    card.onclick = function () {
        location.href = editFormUrl + '?id=' + card.id;
    }
    return card;
}

async function getTables(page, rows, room = '%') {
    var params = 'q=tables&page=' + page + '&rows=' + rows + '&room=' + room;
    const response = await fetch('api/api_get.php?' + params);
    return await response.json();
}

window.onload = function () {
    listContainer = document.querySelector('#list-container');
    paginationElement = document.querySelector('#pagination-element');

    goToPage(getTables, 1);
}