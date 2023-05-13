const showDialog = document.getElementById('showDialog');
const submitOrderDialog = document.getElementById('submitOrderDialog');

showDialog.addEventListener('click', () => {
  submitOrderDialog.showModal();
});
