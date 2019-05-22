<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <base href="{{ asset('') }}">
    <title>Product List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="asset/css/product.css">
</head>
<body>
    <div class="container">
        <div class="row my-4">
            <div class="col-8 offset-2">
                <table id="products" class="cell-border stripe list-product" style="width:100%">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>CreateDate</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>CreateDate</th>
                    </tr>
                    </tfoot>
                </table>
                <table>
                    <tbody>
                        <tr>
                            <th>Tổng sản phẩm: {{ $totalProduct }}</th>
                        </tr>
                        <tr>
                            <th>Tổng tiền: $ {{ $total }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#products tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" id="'+title+'" placeholder="Search '+title+'" />' );
            });

            var table = $('#products').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "http://localhost:8000/api/product",
                "columns": [
                    { "data": "ProductCode" },
                    { "data": "ProductName" },
                    {
                        "data": "Qty",
                        "render": function (qty) {
                            return qty.toLocaleString();
                        }
                    },
                    {
                        "data": "Price",
                        'render': function(price) {
                            return '$ ' + price.toLocaleString();
                        }
                    },
                    {
                        "data": "CreateDate",
                        "render" : function (date) {
                            d = new Date(date)
                            // console.log(d.getDay());
                            return (d.getDate()) + '/' + (d.getMonth()+1) + '/' + d.getFullYear();
                        }
                    }
                ]
            });
            table.columns().every( function () {
                var that = this;

                $('input', this.footer()).on( 'keyup change', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });

        // <input type="search" class="" placeholder="" aria-controls="products">

            // $('#products_filter label').replaceWith('<label><input type="search" class="" placeholder="" aria-controls="products"></label>');

            // console.log($("#products_filter label").html('<input type="search" class="" placeholder="" aria-controls="products">'));

        });
    </script>
</body>
</html>