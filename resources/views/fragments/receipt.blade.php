<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt</title>
    <style>
        body {
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
        }

        .main {
            height: 100vh;
            display: grid;
            justify-content: center;
            align-items: flex-start;
        }

        table,
        th,
        td {
            border: 1px dashed black;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            padding: 5px 10px;
            text-align: left;
        }

        .p-contact {
            margin: 0px;
            font-size: 13px;
        }

        .info {
            padding: 10px;
            margin-top: 2em;
        }

        .table-container {
            width: 50ch;
        }

        @media screen and (max-width: 840px) {
            .table-container {
                width: 30ch;
            }
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="table-container">

            <div class="info">
                <h2 style="margin-bottom: 0; text-transform:uppercase">{{ $store_info['store_name']}}</h2>
                <p class="p-contact">E-mail address: {{ $store_info['store_email'] }} </p>
                <p class="p-contact">Contact no. : {{ $store_info['contact']}} </p>
                <p class="p-contact">Address. : {{ $store_info['address']}} </p>
            </div>

            <table style="width:100%">
                <tr>
                    <!-- <th>Product id</th> -->
                    <th>Items</th>
                    <th>category</th>
                    <th>qty.</th>
                    <th>Subtotal</th>
                </tr>
                @foreach($data as $entry)
                <tr>
                    <!-- <td>{{$entry->product_id}}</td> -->
                    <td>{{$entry->product_name}}</td>
                    <td>{{$entry->category}}</td>
                    <td>{{$entry->qty}}</td>
                    <td>&#8369 {{$entry->price}}</td>
                </tr>
                @endforeach
                <tr>
                    <th>Total no. of items : {{ $item_count}}</th>
                    <th></th>
                    <th></th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>Transaction id: {{$data[0]->transaction_id}} </td>
                    <td></td>
                    <td></td>
                    <th>&#8369 {{ $total_amount}}</th>
                </tr>
            </table>
            <div style="margin-bottom: 5em;">
                <h5 style="margin-bottom: 0; text-transform:uppercase"> CUSTOMER ENTERED AMOUNT : &#8369 {{ $data[0]->customer_amount}}</h5>
                <h5 style="margin-top: 12px; text-transform:uppercase"> CHANGE : &#8369 <span id="change-fee" style="font-size:20px"></span></h5>
                <hr>
                <p class=" p-contact">Date. : {{ $data[0]->created_at}}</p>
                <p class="p-contact" style="text-align:center; margin-top: 20px">Thank you for purchasing!</p>
            </div>
        </div>
    </div>
</body>
<script>
    const customer_amount = "{{ $data[0]->customer_amount}}";
    const due = "{{ $total_amount}}";
    const change = (parseFloat(customer_amount) - parseFloat(due))
    document.getElementById("change-fee").textContent = change.toFixed(2)

    window.onload = () => {
        window.print()
    }
</script>

</html>