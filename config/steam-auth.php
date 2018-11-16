<?php

return [

    /*
     * Redirect URL after login
     */
    'redirect_url' => '/account/steam_bind',
    /*
     * API Key (set in .env file) [http://steamcommunity.com/dev/apikey]
     */
    'api_key' => env('STEAM_API_KEY', 'B3E5AACA37D1682072C8014563D06A53'),
    /*
     * Is using https ?
     */
    'https' => true,

];
