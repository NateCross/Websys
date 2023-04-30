/**
 * Redirect the user to a different page
 * after a certain timeout
 * The timeout is in milliseconds
 * and if no timeout, it is instant
 * @param {string} destination 
 * @param {number} timeout 
 */
export function redirect(destination = '/', timeout = null) {
  setTimeout(() => {
    window.location.replace(destination);
  }, timeout);
}