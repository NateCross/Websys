const showDialog = document.getElementById('showDialog');
const cancel = document.getElementById('cancel');
const submitOrderDialog = document.getElementById('submitOrderDialog');

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

showDialog?.addEventListener('click', () => {
  submitOrderDialog.showModal();
});

cancel?.addEventListener('click', (e) => {
  e.preventDefault();

  submitOrderDialog.close();
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