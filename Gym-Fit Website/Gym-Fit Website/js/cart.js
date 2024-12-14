// Initialize an empty cart array
let cart = [];

// Function to update the cart display (table, subtotal, total)
function updateCartDisplay() {
  const cartItemsContainer = document.getElementById("cart-items");
  let subtotalAmount = 0;

  // Clear current cart items in the table
  cartItemsContainer.innerHTML = "";

  // Loop through the cart and create rows
  cart.forEach((item, index) => {
    const row = document.createElement("tr");

    // Product Info
    const productCell = document.createElement("td");
    productCell.classList.add("d-flex", "align-items-center", "product-info");
    productCell.innerHTML = `
            <img src="${item.image}" alt="${item.name}" class="img-thumbnail me-3" />
            <div>
                <h6 class="mb-0">${item.name}</h6>
                <small>₱${item.price} each</small>
            </div>
        `;
    row.appendChild(productCell);

    // Quantity Input
    const quantityCell = document.createElement("td");
    quantityCell.classList.add("text-center");
    quantityCell.innerHTML = `
            <input type="number" class="form-control quantity-input" value="${item.quantity}" min="1" data-index="${index}" />
        `;
    row.appendChild(quantityCell);

    // Subtotal Calculation
    const subtotalCell = document.createElement("td");
    subtotalCell.classList.add("text-center", "subtotal");
    const itemSubtotal = item.price * item.quantity;
    subtotalCell.textContent = `₱${itemSubtotal.toFixed(2)}`;
    row.appendChild(subtotalCell);

    // Remove Button
    const removeCell = document.createElement("td");
    removeCell.classList.add("text-center");
    removeCell.innerHTML = `<button class="btn btn-danger btn-sm remove-link" data-index="${index}">Remove</button>`;
    row.appendChild(removeCell);

    // Append the row to the table
    cartItemsContainer.appendChild(row);

    // Add to subtotal amount
    subtotalAmount += itemSubtotal;
  });

  // Calculate tax (12%) and total
  const taxAmount = subtotalAmount * 0.12;
  const totalAmount = subtotalAmount + taxAmount;

  // Update the summary fields
  document.getElementById(
    "subtotal-amount"
  ).textContent = `₱${subtotalAmount.toFixed(2)}`;
  document.getElementById("tax-amount").textContent = `₱${taxAmount.toFixed(
    2
  )}`;
  document.getElementById("total-amount").textContent = `₱${totalAmount.toFixed(
    2
  )}`;

  // Update cart badge
  updateCartBadge();
}

// Event listener to update quantity in the cart
document.addEventListener("input", function (event) {
  if (event.target.classList.contains("quantity-input")) {
    const index = event.target.dataset.index;
    const newQuantity = parseInt(event.target.value, 10);

    // Update the quantity in the cart array
    if (newQuantity > 0) {
      cart[index].quantity = newQuantity;
      updateCartDisplay();
    }
  }
});

// Event listener to remove item from the cart
document.addEventListener("click", function (event) {
  if (event.target.classList.contains("remove-link")) {
    const index = event.target.dataset.index;

    // Remove the item from the cart array
    cart.splice(index, 1);
    updateCartDisplay();
  }
});

// Function to update the cart badge
function updateCartBadge() {
  const cartBadge = document.querySelector(".cart-badge");
  cartBadge.textContent = cart.length; // Update the badge count based on the number of items in the cart
}

// Function to add a product to the cart (you can call this when a user adds an item)
function addProductToCart(name, price, image) {
  const newProduct = {
    name: name,
    price: price,
    quantity: 1,
    image: image,
  };
  cart.push(newProduct);
  updateCartDisplay();
}

// Handle "Proceed to Checkout" button
document.querySelector(".proceed-btn").addEventListener("click", function () {
  if (cart.length === 0) {
    alert("Your cart is empty. Please add some products before proceeding.");
  } else {
    alert("Proceeding to checkout...");
    // Here you can add your checkout logic (e.g., redirect, API call)
  }
});

// Initialize the cart display on page load (empty cart)
updateCartDisplay();
