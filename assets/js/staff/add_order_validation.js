document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('orderForm');
  const nameField = document.querySelector('input[name="customer_name"]');
  const emailField = document.querySelector('input[name="customer_email"]');
  const commentField = document.getElementById('comments');
  const finalAmount = document.getElementById('finalAmount');

  const nameError = document.getElementById('nameError');
  const emailError = document.getElementById('emailError');
  const commentError = document.getElementById('commentError');
  const cartError = document.getElementById('cartError');

  form.addEventListener('submit', (e) => {
    let valid = true;

    // Name validation
    const namePattern = /^[a-zA-Z\s]+$/;
    if (!namePattern.test(nameField.value.trim())) {
      nameError.style.display = 'block';
      valid = false;
    } else {
      nameError.style.display = 'none';
    }

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(emailField.value.trim())) {
      emailError.style.display = 'block';
      valid = false;
    } else {
      emailError.style.display = 'none';
    }

    // Comment validation
    const commentPattern = /^[a-zA-Z0-9\s.,!?'-]*$/;
    if (commentField.value.trim().length > 0 && !commentPattern.test(commentField.value.trim())) {
      commentError.style.display = 'block';
      valid = false;
    } else {
      commentError.style.display = 'none';
    }

    // Cart validation
    if (Object.keys(cart).length === 0 || isNaN(parseFloat(finalAmount.value)) || parseFloat(finalAmount.value) <= 0) {
      cartError.style.display = 'block';
      valid = false;
    } else {
      cartError.style.display = 'none';
    }

    if (!valid) {
      e.preventDefault(); 
    }
  });
});