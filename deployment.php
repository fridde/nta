<?php
declare(strict_types=1);

return [
    'Naturskolan' => [
        'remote' => 'ftp://sigtunanaturskola.se/public_html',
        'user' => 'userE@sigtunanaturskola.se',
        'password' => 'aErXjaJBxKBYJRmVwWCD',
        'local' => '.',
        'passiveMode' => true,
        'ignore' => '
			.env.test*
            .git*
            .htaccess
            /assets
            /docs
            /extra	        
            *LICENSE*
            /temp
			/tests
            /var
            /vendor
            deployment*
		',
        'purge' => [
        ],
        'allowDelete' => true,
        'preprocess' => false,
        'deploymentFile' => '.deployment',
    ],

    'colors' => true,
];