const logoutButton = document.getElementById('logout');

async function fetchLogoutScript() {
  const result = await fetch('scripts/_logout.php');
  const response = await result.text();
  document.querySelector('html').innerHTML = response;
}

logoutButton?.addEventListener('click', fetchLogoutScript);