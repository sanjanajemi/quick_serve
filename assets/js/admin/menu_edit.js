 document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
      const errors = {};

      const name = form.querySelector("[name='name']").value.trim();
      const variant = form.querySelector("[name='variant_type']").value.trim();
      const description = form.querySelector("[name='description']").value.trim();
      const price = form.querySelector("[name='price']").value.trim();
      const category = form.querySelector("[name='category']").value.trim();
      const ingredients = form.querySelector("[name='ingredients']").value.trim();
      const status = form.querySelector("[name='status']").value;

      // Name: required, letters/numbers/spaces only
      if (!name || !/^[A-Za-z0-9\s]+$/.test(name)) {
        errors.name = "Name must contain only letters, numbers, and spaces.";
      }

      // Variant type: optional, but if present must be alphanumeric
      if (variant && !/^[A-Za-z0-9\s]+$/.test(variant)) {
        errors.variant_type = "Variant type must be alphanumeric.";
      }

      // Description: optional, but limit length
      if (description && description.length > 500) {
        errors.description = "Description must be less than 500 characters.";
      }

      // Price: required, must be positive number
      if (!price || isNaN(price) || parseFloat(price) <= 0) {
        errors.price = "Price must be a positive number.";
      }

      // Category: optional, must be one of allowed
      const allowedCategories = ["food", "bakery", "beverage"];
      if (category && !allowedCategories.includes(category.toLowerCase())) {
        errors.category = "Category must be Food, Bakery, or Beverage.";
      }

      // Ingredients: optional, but limit length
      if (ingredients && ingredients.length > 300) {
        errors.ingredients = "Ingredients must be less than 300 characters.";
      }

      // Status: must be published or unpublished
      const allowedStatuses = ["published", "unpublished"];
      if (!status || !allowedStatuses.includes(status.toLowerCase())) {
        errors.status = "Invalid status selected.";
      }

      // Clear old errors
      document.querySelectorAll(".error-msg").forEach(el => el.remove());

      // Show errors inline
      for (const field in errors) {
        const input = form.querySelector(`[name='${field}']`);
        if (input) {
          const errorEl = document.createElement("span");
          errorEl.className = "error-msg";
          errorEl.style.color = "red";
          errorEl.textContent = errors[field];
          input.insertAdjacentElement("afterend", errorEl);
        }
      }

      // Stop submission if errors exist
      if (Object.keys(errors).length > 0) {
        e.preventDefault();
      }
    });
  });