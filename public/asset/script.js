window.addEventListener("DOMContentLoaded", () => {

    document.querySelector('.products').dataset.index = "0"
    document.querySelector('.payments').dataset.index = "0"

    const addProductForm = () => {
        const collectionHolder = document.querySelector('.products');

        const item = document.createElement('p');
        item.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );

        const button = createDeleteButton(collectionHolder.dataset.index, "delete_product_link");

        const div = document.createElement('div');

        div.appendChild(item);
        div.appendChild(button);
        collectionHolder.appendChild(div);

        document
            .querySelectorAll('.delete_product_link')
            .forEach(button => {
                button.addEventListener('click', function(event) {
                    console.log(event.target.id)
                    deleteForm(event, ".products")
                });
            });

        collectionHolder.dataset.index++;
    };

    const addPaymentForm = () => {
        const collectionHolder = document.querySelector('.payments');

        const item = document.createElement('p');

        item.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );

        const button = createDeleteButton(collectionHolder.dataset.index, "delete_payment_link");

        const div = document.createElement('div');

        div.appendChild(item);
        div.appendChild(button);
        collectionHolder.appendChild(div);

        document
            .querySelectorAll('.delete_payment_link')
            .forEach(button => {
                button.addEventListener('click', function(event) {
                    deleteForm(event, ".payments")
                });
            });

        collectionHolder.dataset.index++;
    };


    document
        .querySelectorAll('.add_product_link')
        .forEach(btn => btn.addEventListener("click", addProductForm));

    document
        .querySelectorAll('.add_payment_link')
        .forEach(btn => btn.addEventListener("click", addPaymentForm));



    function deleteForm (event, type) {
        event.preventDefault();
        document.querySelector(type).dataset.index--;
        event.target.parentElement.remove();

    }

    const createDeleteButton = (event, type) => {
        let random = (Math.random() * 10000).toString()
        const removeButton = document.createElement('button');
        removeButton.classList.add(type,"btn", "btn-danger", "mb-2");
        removeButton.id = random
        removeButton.innerHTML = 'Delete';
        return removeButton;
    }

    document.querySelectorAll('legend')
        .forEach(elem => {
            elem.parentNode.removeChild(elem);
        });
})
