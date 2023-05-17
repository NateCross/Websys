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

///// Buy Now /////
const buy_now_button = document.getElementById("buy_now_button");
const purchase_dialog = document.getElementById("purchase_dialog");
const buy_now_quantity = document.getElementById("buy_now_quantity");
const buy_now_cancel = document.getElementById("buy_now_cancel");

const bank = document.getElementById('bank');
const bank_other = document.getElementById('bank_other');
const owner = document.getElementById('owner');
const owner_label = document.getElementById('owner_label');
const cvv = document.getElementById('cvv');
const cvv_label = document.getElementById('cvv_label');
const card_number = document.getElementById('card_number');
const card_label = document.getElementById('card_label');
const expiration_date_label = document.getElementById('expiration_date_label');
const expiration_date_month = document.getElementById('expiration_date_month');
const expiration_date_year = document.getElementById('expiration_date_year');

buy_now_button?.addEventListener('click', (e) => {
  e.preventDefault();
  buy_now_quantity.value = quantity_purchased.value;

  purchase_dialog.showModal();
});

buy_now_cancel?.addEventListener('click', (e) => {
  e.preventDefault();

  purchase_dialog.close();
});

bank?.addEventListener('change', (e) => {
  if (e?.target?.value === 'other') {
    bank_other.hidden = false;
    bank_other.required = true;

    owner.hidden = false;
    owner.required = true;
    owner_label.hidden = false;

    cvv.hidden = false;
    cvv.required = true;
    cvv_label.hidden = false;

    card_number.hidden = false;
    card_number.required = true;
    card_number_label.hidden = false;

    expiration_date_year.hidden = false;
    expiration_date_month.hidden = false;
    expiration_date_label.hidden = false;
  } else if (
    e?.target?.value === 'bdo'
    || e?.target?.value === 'bpi'
  ) {
    bank_other.hidden = true;
    bank_other.required = false;

    owner.hidden = false;
    owner.required = true;
    owner_label.hidden = false;

    cvv.hidden = false;
    cvv.required = true;
    cvv_label.hidden = false;

    card_number.hidden = false;
    card_number.required = true;
    card_number_label.hidden = false;

    expiration_date_year.hidden = false;
    expiration_date_month.hidden = false;
    expiration_date_label.hidden = false;
  } else {
    bank_other.hidden = true;
    bank_other.required = false;

    owner.hidden = true;
    owner.required = false;
    owner_label.hidden = true;

    cvv.hidden = true;
    cvv.required = false;
    cvv_label.hidden = true;

    card_number.hidden = true;
    card_number.required = false;
    card_number_label.hidden = true;

    expiration_date_year.hidden = true;
    expiration_date_month.hidden = true;
    expiration_date_label.hidden = true;

    owner.value = '';
    cvv.value = '';
    card_number.value = '';
  }

  // Clear the value so it doesn't persist and create
  // incorrect rows in the database
  bank_other.value = '';
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