const showDialog = document.getElementById('showDialog');
const cancel = document.getElementById('cancel');
const submitOrderDialog = document.getElementById('submitOrderDialog');
const bank = document.getElementById('bank');
const bank_other = document.getElementById('bank_other');

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
  } else {
    bank_other.hidden = true;
  }

  // Clear the value so it doesn't persist and create
  // incorrect rows in the database
  bank_other.value = '';
});