const addpaymentForm = () => {
    const collectionHolder = document.querySelector('.payments');

    const paymentLink = document.createElement('li');

    paymentLink.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    const button = createDeleteButton(collectionHolder.dataset.index);

    collectionHolder.appendChild(paymentLink);
    collectionHolder.appendChild(button);

    document
        .querySelectorAll('.delete_paymentLink_link')
        .forEach(button => {
            button.addEventListener('click', deletepaymentForm);
        });

    collectionHolder.dataset.index++;
};

document
    .querySelectorAll('.add_payment_link')
    .forEach(btn => btn.addEventListener("click", addpaymentForm));


const deletepaymentForm = (event) => {
    event.preventDefault();
    const collectionHolder = document.querySelector('.payments');
    const index = collectionHolder.dataset.index;
    collectionHolder.dataset.index--;
    document.querySelector(`li:nth-of-type(${index})`).remove();
    document.querySelector(`button:nth-of-type(${index})`).remove();
};

const createDeleteButton = (index) => {
    const removeButton = document.createElement('button');
    removeButton.classList.add('delete_paymentLink_link');
    removeButton.innerHTML = 'Delete payment';
    return removeButton;
}