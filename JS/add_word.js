function addKeyValuePair() {
    const container = document.getElementById('keyValuePairsContainer');
    const div = document.createElement('div');
    div.className = 'key-value-pair';

    const keyInput = document.createElement('input');
    keyInput.type = 'text';
    keyInput.name = 'key[]';
    keyInput.className='words';
    keyInput.required=true;
    keyInput.placeholder = 'Key';
    

    const valueInput = document.createElement('input');
    valueInput.type = 'text';
    valueInput.name = 'value[]';
    valueInput.className='words';
    valueInput.required=true;
    valueInput.placeholder = 'Value';

    const deleteIcon = document.createElement('i');
    deleteIcon.className = 'fas fa-trash';
    deleteIcon.onclick = function() {
        container.removeChild(div);
    };

    div.appendChild(keyInput);
    div.appendChild(valueInput);
    div.appendChild(deleteIcon);

    container.appendChild(div);
}