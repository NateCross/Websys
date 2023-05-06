// This script handles the showing and hiding of dialog

const showDialog = document.getElementById('showDialog');
const reportDialog = document.getElementById('reportDialog');

showDialog?.addEventListener('click', () => {
  reportDialog.showModal();
});