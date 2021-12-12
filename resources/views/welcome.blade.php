<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


    </head>

    <body>
        <div class="container mt-5">
            <h4>Pull prices</h4>

            <table class="table pt-2">
                <thead>
                    <tr>
                        <th>Currency</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody id="data-dump">
                </tbody>
            </table>
        </div>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <script>
            if('WebSocket' in window || 'MozWebSocket' in window){
               const socket = new WebSocket('wss://ws.bitstamp.net');
               const currencies = ['btcusd', 'btceur', 'btcgbp', 'btcpax', 'gbpusd', 'gbpeur', 'eurusd', 'xrpusd', 'xrpeur', 'xrpbtc', 'xrpgbp', 'xrppax']
               let html = '';
               currencies.forEach(element => {
                   let index = 1;
                   html += '<tr>'
                    html += `<td id="${element}">${element}</td>`
                    html += `<td id="${element}_price">--</td>`
                    html += '</tr>'
               })
               document.getElementById('data-dump').innerHTML = html

               // Connection opened
            socket.addEventListener('open', e => {
                currencies.forEach((element, index) => {
                    socket.send(JSON.stringify({
                            event: 'bts:subscribe',
                            data:{
                                channel: `live_trades_${element}`
                            }
                    }));
                });
            });




            socket.addEventListener('message', e => {
            // console.log('Message from server ', e.data);
                let data = JSON.parse(e.data)
                if(data.event == 'trade'){
                    let currency = data.channel.slice('live_trades_'.length)
                    document.getElementById(`${currency}_price`).innerHTML = data.data.price_str
                }
            });

            }
        </script>
    </body>

</html>
