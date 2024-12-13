<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        fieldset {
            border: 2px solid black;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: rgb(249, 249, 249);
        }

        legend {
            font-weight: bold;
            text-align: left;
            color: rgb(34, 29, 29);
        }
    </style>
</head>

<body>
    <div>
        <form id="detailsForm">
            @csrf
            <div class="m-3">
                <fieldset>
                    <legend>Product Details</legend>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Error</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mx-5 mt-5">
                        <label for="product_name" class="form-label fw-bold">Product Name</label>
                        <input type="text" id="product_name" name="product_name" class="form-control" required>
                    </div>
                    <div class="mx-5 mt-2">
                        <label for="quantity_in_stock" class="form-label fw-bold">Quantity in Stock</label>
                        <input type="number" id="quantity_in_stock" name="quantity_in_stock" class="form-control"
                            required>
                    </div>
                    <div class="mx-5 mt-2">
                        <label for="price_per_item" class="form-label fw-bold">Price per Item</label>
                        <input type="number" id="price_per_item" name="price_per_item" class="form-control" required>
                    </div>
                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-secondary btn-lg">Save</button>
                    </div>
                </fieldset>
            </div>

        </form>
    </div>
    <div class="m-5">
        <h3>Products Listing</h3>
        <table class="table table-hover table-bordered align-middle text-center" id="productTable">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity in Stock</th>
                    <th>Price per Item</th>
                    <th>Datetime Submitted</th>
                    <th>Total Value Number</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="align-left"></th>
                    <th id="sumOfValueNumber"></th>
                </tr>
            </tfoot>
        </table>
    </div>


    <script>
        $(document).ready(function() {
            const productTable = $("#productTable tbody");
            const sumOfValueNumber = $("#sumOfValueNumber");

            function loadProducts() {
                $.getJSON('/products/json', function(data) {
                    productTable.empty();
                    let total = 0;
                    data.forEach(product => {
                        const totalValue = product.total_value;
                        total += totalValue;

                        productTable.append(`
                            <tr>
                                <td>${product.product_name}</td>
                                <td>${product.quantity_in_stock}</td>
                                <td>${product.price_per_item.toFixed(2)}</td>
                                <td>${product.datetime_submitted}</td>
                                <td>${totalValue.toFixed(2)}</td>
                            </tr>
                        `);
                    });
                    sumOfValueNumber.text(total.toFixed(2));
                });
            }

            $("#detailsForm").on("submit", function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.post('/products', formData, function() {
                    loadProducts();
                    $("#detailsForm")[0].reset();
                });
            });
            loadProducts();
        });
    </script>
</body>

</html>
