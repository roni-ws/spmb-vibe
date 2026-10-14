<?php

namespace Config;

use CodeIgniter\Shield\Config\AuthJWT as ShieldAuthJWT;

class AuthJWT extends ShieldAuthJWT
{
    /**
     * --------------------------------------------------------------------
     * Name of Authenticator Header
     * --------------------------------------------------------------------
     * The name of Header that the Authorization token should be found.
     */
    public string $authenticatorHeader = 'Authorization';

    /**
     * --------------------------------------------------------------------
     * The Default Payload Items
     * --------------------------------------------------------------------
     * All JWTs will have these claims in the payload.
     */
    public array $defaultClaims = [
        'iss' => 'http://localhost:8080/',
    ];

    /**
     * --------------------------------------------------------------------
     * The Keys
     * --------------------------------------------------------------------
     * Keyset configuration.
     */
    public array $keys = [];

    public function __construct()
    {
        parent::__construct();

        // Ensure JWT issuer matches dynamic base url of the application
        $this->defaultClaims['iss'] = base_url();

        // Retrieve secret key from environment or use a secure default for development
        $secretKey = env('JWT_SECRET') ?: 'spmb_vibe_super_secure_jwt_secret_key_1234567890!';

        $this->keys = [
            'default' => [
                [
                    'kid'    => '',
                    'alg'    => 'HS256',
                    'secret' => $secretKey,
                ],
            ],
        ];
    }
}
