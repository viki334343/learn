$(document).ready(function () {
    const products = [
        { id: 1, name: "Smart Headphones", price: 100, category: "Electronics", image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=60" },
        { id: 2, name: "Casual T-Shirt", price: 150, category: "Clothing", image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&auto=format&fit=crop&q=60" },
        { id: 3, name: "Smartphone", price: 200, category: "Electronics", image: "https://plus.unsplash.com/premium_photo-1679913792906-13ccc5c84d44?w=500&auto=format&fit=crop&q=60" },
        { id: 4, name: "Running Shoes", price: 120, category: "Footwear", image: "https://images.unsplash.com/photo-1552196563-55cd4e45efb3?w=500&auto=format&fit=crop&q=60"},
        { id: 5, name: "Smart Watch", price: 250, category: "Electronics", image: "https://images.unsplash.com/photo-1552196563-55cd4e45efb3?w=500&auto=format&fit=crop&q=60"},
        { id: 6, name: "Denim Jacket", price: 180, category: "Clothing", image: "https://images.unsplash.com/photo-1552196563-55cd4e45efb3?w=500&auto=format&fit=crop&q=60"},
        { id: 7, name: "Backpack", price: 90, category: "Accessories", image: "https://images.unsplash.com/photo-1552196563-55cd4e45efb3?w=500&auto=format&fit=crop&q=60"},
        { id: 8, name: "Sunglasses", price: 80, category: "Accessories", image: "https://images.unsplash.com/photo-1552196563-55cd4e45efb3?w=500&auto=format&fit=crop&q=60"},
        { id: 9, name: "Gaming Mouse", price: 60, category: "Electronics", image: "https://images.unsplash.com/photo-1552196563-55cd4e45efb3?w=500&auto=format&fit=crop&q=60"},
        { id: 10, name: "Yoga Mat", price: 50, category: "Fitness", image: "https://images.unsplash.com/photo-1552196563-55cd4e45efb3?w=500&auto=format&fit=crop&q=60" }
    ];
    

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    function renderProducts(filter = "", category = "all") {
        $("#products").empty();
        products
            .filter(product => product.name.toLowerCase().includes(filter.toLowerCase()))
            .filter(product => category === "all" || product.category === category)
            .forEach(product => {
                $("#products").append(`
                    <div class="product">
                        <img src="${product.image}" alt="${product.name}" class="product-image">
                        <h3>${product.name}</h3>
                        <p>Category: ${product.category}</p>
                        <p>Price: $${product.price}</p>
                        <input type="number" class="product-quantity" data-id="${product.id}" value="1" min="1">
                        <button class="add-to-cart" data-id="${product.id}">Add to Cart</button>
                    </div>
                `);
            });
    }

    function renderCart() {
        $("#cart").empty();
        cart.forEach((item, index) => {
            $("#cart").append(`
                <div class="cart-item">
                    <p>${item.name} - $${item.price}</p>
                    <input type="number" class="cart-quantity" data-index="${index}" value="${item.quantity}" min="1">
                    <button class="remove-from-cart" data-index="${index}">Remove</button>
                </div>
            `);
        });

        let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        $("#cart").append(`<h3 class="cart-total">Total: $${total}</h3>`);

        localStorage.setItem("cart", JSON.stringify(cart));
    }

    $(document).on("input", "#product-filter", function () {
        const filterText = $(this).val();
        const selectedCategory = $("#category-filter").val();
        renderProducts(filterText, selectedCategory);
    });

    $(document).on("change", "#category-filter", function () {
        const selectedCategory = $(this).val();
        const filterText = $("#product-filter").val();
        renderProducts(filterText, selectedCategory);
    });

    $(document).on("click", ".add-to-cart", function () {
        const id = $(this).data("id");
        const quantity = parseInt($(`.product-quantity[data-id='${id}']`).val());
        const product = products.find(p => p.id === id);
        const cartItem = cart.find(item => item.id === id);

        if (cartItem) {
            cartItem.quantity += quantity;
        } else {
            cart.push({ ...product, quantity: quantity });
        }
        renderCart();
    });

    $(document).on("click", ".remove-from-cart", function () {
        const index = $(this).data("index");
        cart.splice(index, 1);
        renderCart();
    });

    $(document).on("change", ".cart-quantity", function () {
        const index = $(this).data("index");
        const newQuantity = parseInt($(this).val());
        if (newQuantity > 0) {
            cart[index].quantity = newQuantity;
        } else {
            cart.splice(index, 1);
        }
        renderCart();
    });

    $(".product_list_filter").prepend(`
        <input type="text" id="product-filter" placeholder="Search products...">
        <select id="category-filter">
            <option value="all">All Categories</option>
            <option value="Electronics">Electronics</option>
            <option value="Clothing">Clothing</option>
        </select>
    `);

    renderProducts();
    renderCart();
});
