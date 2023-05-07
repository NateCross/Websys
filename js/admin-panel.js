const showDialog = document.querySelectorAll('.toggle-suspend-dialog');
const suspendDialog = document.getElementById('suspendDialog');
const sellerIdElement = document.getElementById('seller_id');
const reportIdElement = document.getElementById('report_id');
const reportsTable = document.getElementById('reports-table');
const toggleClosedReports = document.getElementById('toggle-closed-reports');

showDialog?.forEach((button) => {
  const [reportId, sellerId] = button?.value.split(' ');

  button?.addEventListener('click', () => {
    sellerIdElement.value = sellerId;
    reportIdElement.value = reportId;
    suspendDialog?.showModal();
  });
});

const [tableHeader, ...tableRows] = reportsTable?.children[0].children;

const openReports = tableRows?.filter((td) => (
  td.children[0].textContent === 'open'
));

const filteredReports = [tableHeader, ...openReports];

// Clear first and show the filtered reports
// so that the behavior of showing only open reports
// is maintained on first load
reportsTable.children[0].innerHTML = '';
reportsTable.children[0].append(...filteredReports);

toggleClosedReports?.addEventListener('change', (e) => {
  const {checked} = e.target;
  reportsTable.children[0].innerHTML = '';
  if (checked) {
    reportsTable.children[0].append(tableHeader, ...tableRows);
  } else {
    reportsTable.children[0].append(...filteredReports);
  }
});