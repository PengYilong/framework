<?php

return [
    'app_init' => [
        ['app\\api\\behavior\\CheckAuth', 'run'],
        '_overlay' =>true,
    ],
];