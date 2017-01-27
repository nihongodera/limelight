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
        'Romaji'   => 'Limelight\Plugins\Library\Romaji\Romaji',
    ],

    /*
    |--------------------------------------------------------------------------
    | Furigana Plugin Options
    |--------------------------------------------------------------------------
    |
    | Define the way that furigana is created. Item is placed where double
    | curly braces ( {{}} ) occur. 'furigana_wrapper' wraps the furigana.
    | 'kanji_wrapper' wraps the kanji. 'kanji_furigana_wrapper' wraps both the
    | kanji and the furigana. 'word_wrapper' wraps the entire word.
    |
    */
    'Furigana' => [
        'furigana_wrapper'       => '<rp>(</rp><rt>{{}}</rt><rp>)</rp>',
        'kanji_furigana_wrapper' => '<ruby>{{}}</ruby>',
        'kanji_wrapper'          => '<rb>{{}}</rb>',
        'word_wrapper'           => '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Romaji Plugin Options
    |--------------------------------------------------------------------------
    |
    | Determines the type of romaji to use. Options are: 'hepburn_modified',
    | 'hepburn_traditional', 'nihon_shiki', and 'kunrei_shiki'.
    |
    */
    'Romaji' => [
        'style' => 'hepburn_modified',
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Listeners
    |--------------------------------------------------------------------------
    |
    | Register listeners for events fired by Limelight. 'WordWasCreated' is
    | fired after a LimelightWord object is created. 'ParseWasSuccessful' is
    | fired after the LimelightResults object is created. Register listeners
    | using their full namespace.
    |
    */
    'listeners' => [
        'WordWasCreated' => [
            //
        ],
        'ParseWasSuccessful' => [
            //
        ],
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
        'Limelight\Mecab\Mecab' => 'Limelight\Mecab\PhpMecab\PhpMecab',
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
            // '-d' => 'path/to/your/dic/directory'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    |
    | Set package debug mode. When true, stack traces will print in terminal.
    |
    */
    'debug' => false,
];
