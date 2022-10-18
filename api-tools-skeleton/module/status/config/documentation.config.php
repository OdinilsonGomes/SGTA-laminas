<?php
return [
    'status\\V1\\Rpc\\Ping\\Controller' => [
        'description' => 'API to the ping',
        'GET' => [
            'description' => 'Testing the API to the ping',
            'response' => '{
"*ack": "Acknowlwdge Response ..."
}',
        ],
    ],
];
