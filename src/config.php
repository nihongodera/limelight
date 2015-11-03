<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Plugins
    |--------------------------------------------------------------------------
    |
    | Register plugins with the classname and the namespace of the plugin.
    |
    */
    'plugins' => [
        'Furigana' => 'Limelight\Plugins\Library\Furigana\Furigana',
        'Romanji' => 'Limelight\Plugins\Library\Romanji\Romanji'
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugin Options
    |--------------------------------------------------------------------------
    |
    | Options should use the name of the plugin class as the key.
    |
    */
    'Furigana' => [
        'furigana_wrapper' => '<rt>{{}}</rt>',
        'kanji_furigana_wrapper' => '<ruby>{{}}</ruby>',
        'kanji_wrapper' => '',
        'word_wrapper' => '',
    ],

    'Romanji' => [
        'style' => 'hepburn_modified'
    ],

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
            'dictionary' => ''
        ]
    ]
];
