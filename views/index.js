const logoutButton = document.getElementById('logout');

async function fetchLogoutScript() {
  const result = await fetch('_logout.php');
  const response = await result.text();
  document.querySelector('body').innerHTML = response;
}

logoutButton.addEventListener('click', fetchLogoutScript);