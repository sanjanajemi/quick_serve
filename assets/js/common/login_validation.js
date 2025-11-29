// --- Utility functions ---

function sanitizeInput(str) {
  const temp = document.createElement('div');
  temp.textContent = str;
  return temp.innerHTML;
}

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email.trim());
}

function validatePassword(password, minLength = 6) {
  return password.length >= minLength;
}

function validateStaffId(staffId) {
  return /^\d{6}$/.test(staffId.trim());
}

//  Attach validation depending on form type 
document.addEventListener('DOMContentLoaded', () => {
  const staffForm = document.getElementById('staffLoginForm');
  const customerForm = document.getElementById('customerLoginForm');
  const adminForm = document.getElementById('adminLoginForm');

  // Staff login rules
  if (staffForm) {
    staffForm.addEventListener('submit', e => {
      const staffId = sanitizeInput(document.getElementById('staff_id').value);
      const password = document.getElementById('password').value;

      if (!validateStaffId(staffId)) {
        alert('Staff ID must be exactly 6 digits.');
        e.preventDefault();
      } else if (!validatePassword(password)) {
        alert('Password must be at least 6 characters long.');
        e.preventDefault();
      }
    });
  }

  // Customer login rules
  if (customerForm) {
    customerForm.addEventListener('submit', e => {
      const email = sanitizeInput(document.querySelector('input[name="email"]').value);
      const password = document.querySelector('input[name="password"]').value;

      if (!validateEmail(email)) {
        alert('Please enter a valid email address.');
        e.preventDefault();
      } else if (!validatePassword(password)) {
        alert('Password must be at least 6 characters long.');
        e.preventDefault();
      }
    });
  }

  // Admin login rules
  if (adminForm) {
    adminForm.addEventListener('submit', e => {
      const adminId = sanitizeInput(document.querySelector('input[name="admin_id"]').value);
      const password = document.querySelector('input[name="password"]').value;

      if (adminId.length === 0) {
        alert('Admin ID is required.');
        e.preventDefault();
      } else if (!validatePassword(password)) {
        alert('Password must be at least 6 characters long.');
        e.preventDefault();
      }
    });
  }
});