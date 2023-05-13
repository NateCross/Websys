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
formatSubtotal();
quantity_purchased?.addEventListener('change', () => {
  formatSubtotal();
});