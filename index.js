const logoutButton = document.getElementById('logout');

async function fetchLogoutScript() {
  const result = await fetch('_logout.php');
  const response = await result.text();
  // console.log(response);
  document.querySelector('html').innerHTML = response;
}

logoutButton?.addEventListener('click', fetchLogoutScript);