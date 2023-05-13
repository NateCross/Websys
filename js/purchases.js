const submit_review = document.querySelectorAll('.submit_review');
const review_dialog = document.getElementById('review_dialog');

const product_id = document.getElementById('product_id');

submit_review?.forEach((button) => {
  const {value} = button;

  button?.addEventListener('click', () => {
    product_id.value = value;
    review_dialog?.showModal();
  });
})