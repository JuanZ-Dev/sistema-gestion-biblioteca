const addCollectionItemLink = () => {
    document
        .querySelectorAll('.add_collection_item:not(.add_item_open)')
        .forEach(btn => {
            btn.classList.add('add_item_open')
            btn.addEventListener("click", addFormToCollection)
        });
}

const addCollectionItemRemoveLink = (item) => {
    const removeIcon = document.createElement('i')
    removeIcon.classList.add('fa', 'fa-times')

    const removeLink = document.createElement('a');
    removeLink.href = '#'
    removeLink.classList.add('btn','btn-sm','btn-icon','btn-danger', 'remove_collection_item')
    removeLink.style.cssText = 'position: absolute; top: 0; right: 0;'
    removeLink.appendChild(removeIcon)

    item.append(removeLink);

    removeLink.addEventListener('click', (e) => {
        e.preventDefault();
        if (confirm('Esta seguro de eliminar este item?')) {
            item.remove();
        }
    });
}
const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector('#' + e.currentTarget.dataset.collectionHolderId);
    const item = document.createElement('li');
    item.classList.add('list-group-item','collection_item', 'pt-2', 'pb-0', 'pe-sm-12')
    const prototypeData = collectionHolder.dataset.prototype
    if(collectionHolder.dataset.hasOwnProperty('prototypeName')){
        let prototypeName = new RegExp(collectionHolder.dataset.prototypeName, 'gi');
        item.innerHTML = prototypeData.replace(prototypeName, collectionHolder.dataset.index);
    }
    else{
        item.innerHTML = prototypeData.replace(/__name__/g, collectionHolder.dataset.index);
    }

    addCollectionItemRemoveLink(item)
    collectionHolder.appendChild(item);
    collectionHolder.dataset.index++;
    addCollectionItemLink();
}

document.addEventListener('DOMContentLoaded', () => {
    addCollectionItemLink();

    document
        .querySelectorAll('li.collection_item')
        .forEach((item) => {
            addCollectionItemRemoveLink(item)
        })
})