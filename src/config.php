<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Interface => Class Bindings
    |--------------------------------------------------------------------------
    |
    | Bind an interface to class.  Resolve the class by passing the full 
    | interface namespace to the make method on Config.
    |
    */
    'bindings' => [
        'Limelight\Mecab\Mecab' => 'Limelight\Mecab\PhpMecab\PhpMecab'
    ],

    /*
    |--------------------------------------------------------------------------
    | Binding Options
    |--------------------------------------------------------------------------
    |
    | Options to be passed when constructing the class name when using the above
    | interface => class bindings.  Options should be listed under the short class
    | name of the instantiated class.
    |
    */
    'options' => [
        'PhpMecab' => [
            'dictionary' => getenv('DIC_DIRECTORY')
        ]
    ]
];
