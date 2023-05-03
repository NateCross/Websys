/**
 * Fetches a PHP script. This is done to emulate getting
 * a form to access a script.
 * @param {string} scriptDirectory The directory of the file to fetch
 */
async function fetchPhpScript(scriptDirectory) {
  if (!scriptDirectory) return false;

  const result = await fetch(scriptDirectory);
  const response = await result.text();
  document.querySelector('html').innerHTML = response;
}