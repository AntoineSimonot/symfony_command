window.addEventListener("DOMContentLoaded", () => {
    const addProductForm = () => {
        const collectionHolder = document.querySelector('.products');

        const item = document.createElement('li');

        item.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );

        const button = createDeleteButton(collectionHolder.dataset.index, "delete_product_link");

        collectionHolder.appendChild(item);
        collectionHolder.appendChild(button);

        document
            .querySelectorAll('.delete_product_link')
            .forEach(button => {
                button.addEventListener('click', function(event) {
                    deleteForm(event, ".products")
                });
            });

        collectionHolder.dataset.index++;
    };

    const addPaymentForm = () => {
        const collectionHolder = document.querySelector('.payments');

        const item = document.createElement('li');

        item.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );

        const button = createDeleteButton(collectionHolder.dataset.index, "delete_payment_link");

        collectionHolder.appendChild(item);
        collectionHolder.appendChild(button);

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
        const collectionHolder = document.querySelector(type);
        console.log(collectionHolder)
        const index = collectionHolder.dataset.index;
        collectionHolder.dataset.index--;
        document.querySelector(`${type} li:nth-of-type(${index})`).remove();
        document.querySelector(`${type} button:nth-of-type(${index})`).remove();
    };

    const createDeleteButton = (event, type) => {
        const removeButton = document.createElement('button');
        removeButton.classList.add(type);
        removeButton.innerHTML = 'Delete';
        return removeButton;
    }
})
