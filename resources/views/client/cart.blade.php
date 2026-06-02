<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
        content="{{ csrf_token() }}">

    <title>Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <h1 class="mb-4">
            Shopping Cart
        </h1>

        <div id="cart-items"></div>

        <div class="mt-4">

            <h3>
                Total:
                <span id="total">
                    0
                </span>
                VNĐ
            </h3>

        </div>

        <a href="{{ route('client.checkout') }}"
            class="btn btn-success mt-3">

            Checkout

        </a>

    </div>

    <script>
        let cart =
            JSON.parse(localStorage.getItem('cart')) || [];

        async function loadCart() {
            const cartContainer =
                document.getElementById('cart-items');

            if (cart.length === 0) {
                cartContainer.innerHTML =
                    `
                    <div class="alert alert-warning">
                        Cart is empty
                    </div>
                `;

                return;
            }

            try {

                const response =
                    await fetch('/api/v1/cart/summary', {

                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .content
                        },

                        body: JSON.stringify({
                            items: cart
                        })

                    });

                const result =
                    await response.json();

                let html = '';

                result.data.items.forEach(item => {

                    html += `
                    <div class="card mb-3">

                        <div class="card-body">

                            <div class="row align-items-center">

                                <div class="col-md-2">

                                    <img
                                        src="/storage/${item.product.thumbnail}"
                                        class="img-fluid">

                                </div>

                                <div class="col-md-3">

                                    <h5>
                                        ${item.product.name}
                                    </h5>

                                </div>

                                <div class="col-md-2">

                                    ${Number(item.unit_price)
                                        .toLocaleString()} VNĐ

                                </div>

                                <div class="col-md-2">

                                    Quantity:
                                    ${item.quantity}

                                </div>

                                <div class="col-md-2">

                                    ${Number(item.line_total)
                                        .toLocaleString()} VNĐ

                                </div>

                                <div class="col-md-1">

                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="removeItem(${item.product.id})">

                                        X

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>
                `;
                });

                cartContainer.innerHTML = html;

                document.getElementById('total').innerText =
                    Number(result.data.total).toLocaleString();

            } catch (error) {
                console.error(error);

                cartContainer.innerHTML =
                    `
                    <div class="alert alert-danger">
                        Error loading cart
                    </div>
                `;
            }
        }

        function removeItem(productId) {
            cart = cart.filter(item =>
                item.product_id !== productId);

            localStorage.setItem(
                'cart',
                JSON.stringify(cart)
            );

            loadCart();
        }

        loadCart();
    </script>

</body>

</html>