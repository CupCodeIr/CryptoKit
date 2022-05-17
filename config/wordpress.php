<?php


return [
    'image_size' => env('WORDPRESS_IMAGE_SIZE','post-thumbnails'),
    'base_url' => env('WORDPRESS_BASE_URL','https://cryptokit.best/blog/'),
    'uploads_url' => env('WORDPRESS_UPLOAD_URL','https://cryptokit.best/blog/wp-content/uploads/'),
    'coins_id' => env('WORDPRESS_COINS_ID',false),
    'wallets_id' => env('WORDPRESS_WALLETS_ID',false),
    'exchanges_id' => env('WORDPRESS_EXCHANGES_ID',false),
    'mining_companies_id' => env('WORDPRESS_MINING_COMPANIES_ID',false),
    'mining_equipments_id' => env('WORDPRESS_MINING_EQUIPMENTS_ID',false),
    'mining_pools_id' => env('WORDPRESS_MINING_POOLS_ID',false),
    'crypto_map_id' => env('WORDPRESS_CRYPTO_MAP_ID',false),
];
