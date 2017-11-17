<?php
return [
    'default' => extension_loaded('redis') ? 'redis' : 'file',
];
