var container;
var json;
var jsonStringInput;

function displayTablesList() {
    container.innerHTML = '';
    container.appendChild(getInputElement(json));

    let list = document.createElement('ul');
    let i = 0;
    while (i < json.length) {
        list.appendChild(getListElement(json, i));
        i++;
    }

    container.appendChild(list);
    jsonStringInput.value = JSON.stringify(json);
}

function getListElement(json, index) {
    let listElement = document.createElement('li');
    listElement.id = 'table-' + json[index];
    
    let deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.innerHTML = 'X';
    deleteBtn.onclick = function () {
        json.splice(index, 1);
        displayTablesList();
    }

    let text = document.createElement('span');
    text.innerHTML = json[index];

    listElement.appendChild(deleteBtn);
    listElement.appendChild(text);

    return listElement;
}

function getInputElement(json) {
    let wrapper = document.createElement('div');

    let inputElement = document.createElement('input');
    inputElement.placeholder = 'Numero tavolo...';
    inputElement.type = 'number';

    let addBtn = document.createElement('button');
    addBtn.type = 'button';
    addBtn.innerHTML = '+';
    addBtn.onclick = function () {
        let value = inputElement.value.replace(/\s+/g, '');
        if (value != '' && !json.includes(value)) {
            json.push(value);
            displayTablesList();
        }
    }

    wrapper.appendChild(inputElement);
    wrapper.appendChild(addBtn);
    return wrapper;
}

window.onload = function () {
    container = document.querySelector("#tables-list-container");
    jsonStringInput = document.querySelector("#tables-json-string");
    json = JSON.parse(jsonStringInput.value);

    displayTablesList();
}