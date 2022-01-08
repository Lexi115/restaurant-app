const editFormUrl = location.href + '/../edit_forms/edit_reservation.php';

// Crea una scheda con le informazioni di un record
function createCard(record) {
    let card = document.createElement('div');
    card.classList.add('card');
    card.id = record['cod_prenotazione'];

    // Intestazione
    let cardHeader = document.createElement('h2');
    cardHeader.innerHTML = 'P. ' + card.id;

    // Corpo con informazioni
    let cardBody = document.createElement('p');
    cardBody.appendChild(createCardBodyElement(record['descrizione_status']));
    cardBody.appendChild(createCardBodyElement(record['cognome'] + ' ' + record['nome']));
    cardBody.appendChild(createCardBodyElement(record['data']));
    cardBody.appendChild(createCardBodyElement('Numero persone: ' + record['n_persone']));

    card.appendChild(cardHeader);
    card.appendChild(cardBody);

    card.onclick = function () {
        location.href = editFormUrl + '?id=' + card.id;
    }
    return card;
}

async function getReservations(page, rows, status = '%') {
    var params = 'q=reservations&page=' + page + '&rows=' + rows + '&status=' + status;
    const response = await fetch('api/api_get.php?' + params);
    return await response.json();
}

window.onload = function () {
    listContainer = document.querySelector('#list-container');
    paginationElement = document.querySelector('#pagination-element');

    goToPage(getReservations);
}
