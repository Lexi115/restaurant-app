const editFormUrl = location.href + '/../edit_forms/edit_account.php';

function createCard(record) {
    let card = document.createElement('div');
    card.classList.add('card');
    card.id = record['username'];

    // Intestazione
    let cardHeader = document.createElement('h2');
    cardHeader.innerHTML = 'A. ' + card.id;

    // Corpo con informazioni
    let cardBody = document.createElement('p');
    cardBody.appendChild(createCardBodyElement(record['nome_gruppo']));

    card.appendChild(cardHeader);
    card.appendChild(cardBody);

    card.onclick = function () {
        location.href = editFormUrl + '?id=' + card.id;
    }
    return card;
}

async function getAccounts(page, rows, group = '%') {
    var params = 'q=accounts&page=' + page + '&rows=' + rows + '&group=' + group;
    const response = await fetch('api/api_get.php?' + params);
    return await response.json();
}

window.onload = function () {
    listContainer = document.querySelector('#list-container');
    paginationElement = document.querySelector('#pagination-element');

    goToPage(getAccounts);
}