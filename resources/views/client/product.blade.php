<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>Products</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <div class="d-flex justify-content-between mb-4">

        <h1>
            Products
        </h1>

        <a href="/cart"
           class="btn btn-success">

            Cart

        </a>

    </div>

    <div class="row"
         id="product-list">

    </div>

</div>

<script>

    async function loadProducts()
    {
        try {

            const response =
                await fetch('/api/products');

            const result =
                await response.json();

            let html = '';

            result.data.data.forEach(product => {

                const price =
                    product.sale_price ??
                    product.price;

                html += `

                    <div class="col-md-3 mb-4">

                        <div class="card h-100">

                            <img
                                src="/storage/${product.thumbnail}"
                                class="card-img-top"
                                style="height:250px;object-fit:cover;">

                            <div class="card-body d-flex flex-column">

                                <h5 class="card-title">

                                    ${product.name}

                                </h5>

                                <p class="card-text">

                                    ${Number(price)
                                        .toLocaleString()} VNĐ

                                </p>

                                <button
                                    class="btn btn-primary mt-auto"
                                    onclick="addToCart(${product.id})">

                                    Add To Cart

                                </button>

                            </div>

                        </div>

                    </div>
                `;
            });

            document.getElementById('product-list')
                .innerHTML = html;

        }
        catch(error)
        {
            console.error(error);

            document.getElementById('product-list')
                .innerHTML =
                `
                    <div class="alert alert-danger">
                        Error loading products
                    </div>
                `;
        }
    }

    function addToCart(productId)
    {
        let cart =
            JSON.parse(localStorage.getItem('cart')) || [];

        let found =
            cart.find(item =>
                item.product_id === productId
            );

        if(found)
        {
            found.quantity += 1;
        }
        else
        {
            cart.push({
                product_id: productId,
                quantity: 1
            });
        }

        localStorage.setItem(
            'cart',
            JSON.stringify(cart)
        );

        alert('Added to cart');
    }

    loadProducts();

</script>

</body>
</html>