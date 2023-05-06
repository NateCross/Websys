// This script handles the showing and hiding of dialog

const showDialog = document.querySelectorAll('.toggle-suspend-dialog');
const suspendDialog = document.getElementById('suspendDialog');
const sellerId = document.getElementById('seller_id');
const reportsTable = document.getElementById('reports-table');
const toggleClosedReports = document.getElementById('toggle-closed-reports');

showDialog?.forEach((button) => {
  const {value} = button;

  button?.addEventListener('click', () => {
    sellerId.value = value;
    suspendDialog?.showModal();
  });
});

const [tableHeader, ...tableRows] = reportsTable?.children[0].children;

const openReports = tableRows?.filter((td) => (
  td.children[0].textContent === 'open'
));

const filteredReports = [tableHeader, ...openReports];

toggleClosedReports?.addEventListener('change', (e) => {
  const {checked} = e.target;
  reportsTable.children[0].innerHTML = '';
  if (checked) {
    reportsTable.children[0].append(...filteredReports);
  } else {
    reportsTable.children[0].append(tableHeader, ...tableRows);
  }
});