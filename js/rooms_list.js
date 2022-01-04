const editFormUrl = location.href + '/../edit_forms/edit_room.php';

function createCard(record) {
    let card = document.createElement('div');
    card.classList.add('card');
    card.id = record['cod_sala'];

    // Intestazione
    let cardHeader = document.createElement('h2');
    cardHeader.innerHTML = 'S. ' + card.id;

    // Corpo con informazioni
    let cardBody = document.createElement('p');
    cardBody.appendChild(createCardBodyElement(record['nome_sala']));
    cardBody.appendChild(createCardBodyElement(record['nome_tipo_sala']));

    card.appendChild(cardHeader);
    card.appendChild(cardBody);

    card.onclick = function () {
        location.href = editFormUrl + '?id=' + card.id;
    }
    return card;
}

async function getRooms(page, rows, type = '%') {
    var params = 'q=rooms&page=' + page + '&rows=' + rows + '&type=' + type;
    const response = await fetch('api/api_get.php?' + params);
    return await response.json();
}

window.onload = function () {
    listContainer = document.querySelector('#list-container');
    paginationElement = document.querySelector('#pagination-element');

    goToPage(getRooms);
}