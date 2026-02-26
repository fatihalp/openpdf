<?php

return [
    'queue_connection' => env('REPORTS_QUEUE_CONNECTION', env('QUEUE_CONNECTION', 'sync')),
    'queue_name' => env('REPORTS_QUEUE', 'default'),
    'status_poll_seconds' => (int) env('REPORTS_STATUS_POLL_SECONDS', 3),
];
