// This script handles the showing and hiding of dialog

const showDialog = document.getElementById('showDialog');
const reportDialog = document.getElementById('reportDialog');
const quantity_purchased = document.getElementById('quantity_purchased');
const product_price = document.getElementById('product_price');
const subtotal = document.getElementById('subtotal');

showDialog?.addEventListener('click', () => {
  reportDialog.showModal();
});

/**
 * Format the currency displayed in subtotal whenever
 * the quantity is changed
 * Executes the function immediately on load and on change
 */
function formatSubtotal() {
  const value = product_price?.value 
    * quantity_purchased?.value;

  subtotal.innerHTML = new Intl.NumberFormat('en', {
    style: 'currency',
    currency: 'PHP',
  }).format(value >= 0 ? value : 0);
}
if (subtotal) formatSubtotal();
quantity_purchased?.addEventListener('change', () => {
  formatSubtotal();
});

///// Reviews /////
const edit_review_dialog = document.getElementById('edit_review_dialog');
const review_actions_edit = document.querySelectorAll('.review-actions-edit');
const review_id = document.getElementById('review_id');

const review_actions_delete = document.querySelectorAll('.review-actions-delete');
const delete_review_dialog = document.getElementById('delete_review_dialog');
const review_id_delete = document.getElementById('review_id_delete');

review_actions_edit?.forEach((button) => {
  const {value} = button;

  button.addEventListener('click', () => {
    review_id.value = value;
    edit_review_dialog?.showModal();
  });
});

review_actions_delete?.forEach((button) => {
  const {value} = button;

  button.addEventListener('click', () => {
    review_id_delete.value = value;
    delete_review_dialog?.showModal();
  });
});