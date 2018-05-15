<?php

return [
    'quadx' => [
        'api_base_url' => 'https://api.staging.lbcx.ph/v1/',
        'timezone' => 'Asia/Manila',
        //ideally this will be from user input
        'default_products' => [
            '0077-0424-NSHE',
            '0077-0516-VBTW',
            '0077-0522-QAYC',
            '0077-0526-EBDW',
            '0077-0836-PEFL',
            '0077-1456-TESV',
            '0077-6478-DMAR',
            '0077-6490-VNCM',
            '0077-6491-ASLK',
            '0077-6495-AYUX'
        ]
    ],
    'modules' => [
        'QuadxOrder'
    ]
];
