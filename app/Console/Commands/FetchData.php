<?php

namespace App\Console\Commands;

use WebSocket\Client;
use Illuminate\Console\Command;
use WebSocket\ConnectionException;

class FetchData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a websocket connection to fetch data from a socket client';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client("wss://ws.bitstamp.net.", ['timeout' => 20]);
        $payload = [
            "event" => "bts:subscribe",
            "data" => [
                "channel" => "live_trades_btcusd"
            ]
        ];
        $client->text(json_encode($payload));

        while (true) {
            try {
                $message = $client->receive();
                print_r($message);
                echo "\n";
            } catch (ConnectionException $e) {
                // Possibly log errors
                print_r("Error: " . $e->getMessage());
                echo "\n";
            }
        }
        $client->close();

        return Command::SUCCESS;
    }
}
