# Limelight   
[![Build Status](https://travis-ci.org/nihongodera/limelight.svg?branch=master)](https://travis-ci.org/nihongodera/limelight)
[![Latest Stable Version](https://poser.pugx.org/nihongodera/limelight/version.svg)](//packagist.org/packages/nihongodera/limelight) 
[![License](https://poser.pugx.org/nihongodera/limelight/license.svg)](//packagist.org/packages/nihongodera/limelight)  
##### A php Japanese language analyzer and parser.  
  - Split Japanese text into individual, full words
  - Find parts of speech for words
  - Find dictionary entries (lemmas) for conjugated words
  - Get readings and pronunciations for words
  - Build fuirgana for words

## Contents  
  - [Quick Guide](#quick-guide)
    - [Laravel](#laravel)
  - [Installation](#installation)
  - [Usage](#usage)
    - [Parsing Strings](#parsing-strings)
    - [Using the LimelightResults Class](#using-the-limelightresults-class)
    - [Using the LimelightWord Class](#using-the-limelightword-class)
    - [Doing Raw MeCab Queries](#doing-raw-mecab-queries)
  - [Plugins](#plugins)
    - [Furigana](#furigana)
    - [Romanji](#romanji)
    - [Making Plugins](#making-plugins)
  - [Change Log](#change-log)
  - [Sources, Contributions, and Contributing](#sources-contributing-and-contributing)

## Quick Guide  
[Install MeCab and the php extension php-mecab](https://github.com/nihongodera/php-mecab-documentation) on your system.  
  
Install Limelight.
```
composer require nihongodera/limelight
```
  
Copy the **.env.example** file to **.env**. If your MeCab install requires a dictionary directory, set **DIC_DIRECTORY** to your MeCab dictionary directory path.  Otherwise, leave it as it is.
  
Make a Limelight instance.
```php
$limelight = new Limelight();
```

Parse your text.
```php
$results = $limelight->parse('レモンよりライムの方が好きです。');
```
  
Get the results.
```php
foreach ($results->getNext() as $word) {
    echo "{$word->word} ({$word->reading}) is a {$word->partOfSpeech}.\n";
}

// Output
//
// レモン (レモン) is a noun.
// より (ヨリ) is a postposition.
// ライム (ライム) is a noun.
// の (ノ) is a postposition.
// 方 (ホウ) is a noun.
// が (ガ) is a postposition.
// 好き (スキ) is a adjective.
// です (デス) is a verb.
// 。 (。) is a symbol.
```
  
Check out the [usage documentation](#usage) for more information.   

#### Laravel
  
A Laravel service provider and a facade are included.  After installing Limelight with composer, add the service provider and, if you wish, the facade to your config/app.php file.  
Add the service provider to 'providers'.
```php
'Limelight\Providers\Laravel\LimelightServiceProvider'
```
And add the facade to 'aliases'.
```php
'Limelight' => 'Limelight\Providers\Laravel\Limelight'
```
  
[Top](#contents)
  
## Installation 
Before using Limelight, you must install MeCab and the php-extension 'php-mecap' on your system. See [here](https://github.com/nihongodera/php-mecab-documentation) for details on how to do that.
  
Once installed and working, install Limelight through composer.
```
composer require nihongodera/limelight
```
  
After installation, copy the **.env.example** file to **.env**. If your MeCab install requires a dictionary directory, set **DIC_DIRECTORY** to your MeCab dictionary directory path.  Otherwise, leave it as it is. 
  
## Usage  
  - [Parsing Strings](#parsing-strings)
  - [Using the LimelightResults Class](#using-the-limelightresults-class)
  - [Using the LimelightWord Class](#using-the-limelightword-class)
  - [Doing Raw MeCab Queries](#doing-raw-mecab-queries)
  
### Parsing Strings
  
Create a new Limelight instance and pass text to its parse() method.
```php
$limelight = new Limelight();

$results = $limelight->parse('ライムが好きだけどライム色はちょっと変だと思います。');
```

The parse() method returns an instance of LimelightResults.  
  
### Using the LimelightResults Class
Limelight's parse() method returns a single instance of LimelightResults.  Use it to access the data returned by parse().
  
To get an overview of the results, echo the returned LimelightResults class.
```php
$results = $limelight->parse('ライムは美味しいです。');

echo $results;

// Output
// 
// Word:           ライム
// Part of Speech: noun
// Lemma:          ライム
// Reading:        ライム
// Pronunciation:  ライム
// 
// Word:           は
// Part of Speech: postposition
// Lemma:          は
// Reading:        ハ
// Pronunciation:  ワ
// 
// Word:           美味しい
// Part of Speech: adjective
// Lemma:          美味しい
// Reading:        オイシイ
// Pronunciation:  オイシイ
// 
// Word:           です
// Part of Speech: verb
// Lemma:          です
// Reading:        デス
// Pronunciation:  デス
// 
// Word:           。
// Part of Speech: symbol
// Lemma:          。
// Reading:        。
// Pronunciation:  。
```
  
Get the original string with the getOriginal() method.
``` php
$results = $limelight->parse('メキシコ料理だたらライムが必要です。');

echo $results->getOriginal();

// Output
// 
// 'メキシコ料理だたらライムが必要です。'
```
  
Get an array of all words with the getAll() method.  getAll() returns an array of [LimelightWord](#using-the-limelightword-class) objects.
```php
$results = $limelight->parse('この人ライムが好きすぎる。。。');

$words = $results->getAll();
```
  
Get a single word by index with the getByIndex() method or get a word by the actual word with the getByWord() method.
```php
$results = $limelight->parse('ライムジュース飲もうかな。');

$word1 = $results->getByIndex(0);

$word2 = $results->getByWord('ジュース');

echo 'Word1: ' . $word1->word . ' Word2: ' . $word2->word;

// Output
// 
// Word1: ライム Word2: ジュース
```
  
Loop through words on the LimelightResults object with the getNext() generator method.
```php
$results = $limelight->parse('イギリス人のことを ”ライム野郎 （limey）” と呼びます。');

foreach ($results->getNext() as $word) {
    echo $word->partOfSpeech . "\n";
}

// Output
// 
// proper noun
// postposition
// noun
// postposition
// symbol
// noun
// noun
// symbol
// proper noun
// symbol
// symbol
// postposition
// verb
// symbol
```
  
### Using the LimelightWord Class
Individual words are stored in an array on the LimelightResults class.  Each word is an instance of LimelightWord.

To see an overview of all the word's info, echo the LimelightWord object.
```php
$results = $limelight->parse('ライムを使ったカクテルはすごく美味しいです。');

$wordObject = $results->getByIndex(0);

echo $wordObject;

// Output
// 
// Word:           ライム
// Part of Speech: noun
// Lemma:          ライム
// Reading:        ライム
// Pronunciation:  ライム
```

All information on the LimelightWord object can be accessed by either getting the property ($word->reading) or using the property method plus get() ($word->reading()->get()).  

Access the words raw MeCab data. Output will be an array of MeCab tokens where each token will be an array of the 11 MeCab fields. 
```php
// $word is ライム
$mecabData = $wordObject->rawMecab;
// Or
$mecabData = $wordObject->rawMecab()->get();

// Value of $mecabData
// 
// Array
// (
//     [0] => Array
//         (
//             [type] => parsed
//             [literal] => ライム
//             [partOfSpeech1] => meishi
//             [partOfSpeech2] => 一般
//             [partOfSpeech3] => *
//             [partOfSpeech4] => *
//             [inflectionType] => *
//             [inflectionForm] => *
//             [lemma] => ライム
//             [reading] => ライム
//             [pronunciation] => ライム
//         )
// 
// )
```
In some cases, words are formed of multiple tokens.
```php
// $wordObject is 行っています
$mecabData = $wordObject->rawMecab;

// Value of $mecabData
// 
// Array
// (
//     [0] => Array
//         (
//             [type] => parsed
//             [literal] => 行っ
//             [partOfSpeech1] => doushi
//             [partOfSpeech2] => 自立
//             [partOfSpeech3] => *
//             [partOfSpeech4] => *
//             [inflectionType] => 五段・カ行促音便
//             [inflectionForm] => 連用タ接続
//             [lemma] => 行く
//             [reading] => イッ
//             [pronunciation] => イッ
//         )
// 
//     [1] => Array
//         (
//             [type] => parsed
//             [literal] => て
//             [partOfSpeech1] => joshi
//             [partOfSpeech2] => setsuzokujoshi
//             [partOfSpeech3] => *
//             [partOfSpeech4] => *
//             [inflectionType] => *
//             [inflectionForm] => *
//             [lemma] => て
//             [reading] => テ
//             [pronunciation] => テ
//         )
// 
//     [2] => Array
//         (
//             [type] => parsed
//             [literal] => い
//             [partOfSpeech1] => doushi
//             [partOfSpeech2] => hijiritsu
//             [partOfSpeech3] => *
//             [partOfSpeech4] => *
//             [inflectionType] => 一段
//             [inflectionForm] => 連用形
//             [lemma] => いる
//             [reading] => イ
//             [pronunciation] => イ
//         )
// 
//     [3] => Array
//         (
//             [type] => parsed
//             [literal] => ます
//             [partOfSpeech1] => jodoushi
//             [partOfSpeech2] => *
//             [partOfSpeech3] => *
//             [partOfSpeech4] => *
//             [inflectionType] => tokushuMasu
//             [inflectionForm] => 基本形
//             [lemma] => ます
//             [reading] => マス
//             [pronunciation] => マス
//         )
// 
// )
```
  
Get the word from the LimelightWord object.
```php
// $wordObject is ライム
$word = $wordObject->word;
// Or
$word = $wordObject->word()->get();

// Value of $word
// 
// ライム
```
  
Get the lemma (dictionary form) from the LimelightWord object.
```php
// $wordObject is 食べます
$lemma = $wordObject->lemma;
// Or
$lemma = $wordObject->lemma()->get();

// Value of $lemma
// 
// 食べる
```
  
Get the reading of the word from the LimelightWord object.
```php
// $wordObject is 食べます
$reading = $wordObject->reading;
// Or
$reading = $wordObject->reading()->get();

// Value of $reading
// 
// タベマス
```
  
Get the pronunciation of the word from the LimelightWord object.
```php
// $wordObject is 東京
$pronunciation = $wordObject->pronunciation;
// Or
$pronunciation = $wordObject->pronunciation()->get();

// Value of $pronunciation
// 
// トーキョー
```
  
Get the partOfSpeech of the word from the LimelightWord object.
```php
// $wordObject is 東京
$partOfSpeech = $wordObject->partOfSpeech;
// Or
$partOfSpeech = $wordObject->partOfSpeech()->get();

// Value of $partOfSpeech
// 
// proper noun
```
  
Convert a property to hiragana with toHiragana().
```php
// $wordObject is 東京
echo $wordObject->reading;  // Output: トウキョウ

echo $wordObject->reading()->toHiragana()->get(); // Output: とうきょう
```

Convert a property to katakana with toKatakana().
```php
// $wordObject is おいしい
echo $wordObject->word;  // Output: おいしい

echo $wordObject->word()->toKatakana()->get(); // Output: オイシイ
```
  
### Doing Raw MeCab Queries
  
Some raw MeCab queries can be made from the Limelight object.  

Access MeCab's parseToNode() method with mecabToNode().
```php
$node = $limelight->mecabToNode('食べます');
```
mecabToNode() returns an instance of Limelight\Mecab\Node which wraps the MeCab_Node object.  This has similar functionality to MeCab_Node, but may not be desirable in all situations.  If you wish to get an instance of MeCab_Node, use mecabToMecabNode().
```php
$node = $limelight->mecabToMecabNode('食べます');
```

Access MeCab's parseToString() method with mecabToString().
```php
$string = $limelight->mecabToString('食べます');
```
  
Access MeCab's split() method with mecabSplit().
```php
$array = $limelight->mecabSplit('食べます');
```
  
[Top](#contents)
  
## Plugins
  - [Furigana](#furigana)
  - [Romanji](#romanji)
  - [Making Plugins](#making-plugins)
  
Plugins make it easy to use the information gained from Limelight and allow users to customize the program to improve performance and get only the results they need.  To register a plugin, list it and the full namespace of the class in the 'plugin' array in config.php.
```php
'plugins' => [
    'Furigana' => 'Limelight\Plugins\Plugins\Furigana'
],
```

Any options that the plugin needs are also registerd in config.php in an array where the key is the name of the plugin class.
```php
'Furigana' => [
    'furigana_wrapper' => '<rt>{{}}</rt>',
    'kanji_furigana_wrapper' => '<ruby>{{}}</ruby>',
    'kanji_wrapper' => '',
    'word_wrapper' => '',
],
```
  
Plugins can put results on individual LimelightWord objects, on the LimelightResults object, or both.  To access the plugin data, a few choices exist.  First, call the 'plugin()' method on either LimelightWord or LimelightResults and pass the name of the plugin as parameter.
```php
$limelight = new Limelight();

$results = $limelight->parse('東京に行きます');

$word = $results->getByIndex(0);

echo $word->plugin('Furigana'); // Output: <ruby>東京<rt>とうきょう</rt></ruby>に<ruby>行<rt>い</rt></ruby>きます

echo $results->plugin('Furigana'); // Output: <ruby>東京<rt>とうきょう</rt></ruby>に<ruby>行<rt>い</rt></ruby>きます
```
   
Plugin data can also be accesed on LimelightWord objects in the same way other properties can be accesed by using either the property name or the property method call.
```php
$limelight = new Limelight();

$results = $limelight->parse('東京に行きます');

$word = $results->getByIndex(0);

echo $word->romanji; // Output: Toukyou

echo $word->romanji()->get(); // Output: Toukyou
```

### Furigana
  
The Furigana plugin builds furigana for each word made by Limelight.  By default, furigana is made using the HTML5 ruby tag.  However, for users who wish to do something different, tags can be configured in config.php.
```php
'Furigana' => [
    'furigana_wrapper' => '<rt>{{}}</rt>',
    'kanji_furigana_wrapper' => '<ruby>{{}}</ruby>',
    'kanji_wrapper' => '',
    'word_wrapper' => '',
],
```
Use double curly braces as a place holder between opening and closing tags.  
  
Wrappers are applied in the following order:
```php
<word_wrapper><kanji_furigana_wrapper><kanji_wrapper>**kanji**</kanji_wrapper><furigana_wrapper>**furigana**</furigana_wrapper></kanji_furigana_wrapper>**other characters**</word_wrapper>
```
  
Access the furigana strings on either the LimelightResults object or the LimelightWord objects.
```php
$limelight = new Limelight();

$results = $limelight->parse('東京に行きます');

$word = $results->getByIndex(0);

echo $word->furigana; // Output: <ruby>東京<rt>とうきょう</rt></ruby>に<ruby>行<rt>い</rt></ruby>きます

echo $results->plugin('Furigana'); // Output: <ruby>東京<rt>とうきょう</rt></ruby>に<ruby>行<rt>い</rt></ruby>きます
```
  
### Romanji
  
The Romanji plugin converts words from Japanese to romanji (English letters).  Currently, only [traditional hepburn](https://en.wikipedia.org/wiki/Hepburn_romanization) romanization is available, but other options are coming soon.  

To get romanji for a string, parse it and access it on the LimelightResults object.
```php
$limelight = new Limelight();

$results = $limelight->parse('東京に行きます');

echo $results->plugin('Romanji'); // Output: Toukyou ni ikimasu
```
Strings on the LimelightResults object are space seperated.  

Results can also be accessed on LimelightWord objects.
```php
$limelight = new Limelight();

$results = $limelight->parse('東京に行きます');

foreach ($results->getNext() as $word) {
    echo $word->romanji;
}

// Output
//
// Toukyouniikimasu
```
  
Proper nouns are capitalized.  
  
### Making Plugins
  
Making plugins for Limelight is simple.  First, create a plugin class and have it extend Limelight\Plugins\Plugin.  Limelight\Plugins\Plugin has one abstract method, handle(), which you must implement.
```php
use Limelight\Plugins\Plugin;

class Example extends Plugin
{
    public function handle()
    {

    }
}
```
  
If you need to use a constructor, be sure to pass the arguments up to the parent.  
```php
public function __construct($text, $node, $tokens, $words)
{
    // Construct what you need

    parent::__construct($text, $node, $tokens, $words);
}
```
  
The parent object has the following properties on it:  
  - $text - The original, user inputed text.
  - $node - An instance of Limelight\Mecab\Node which can be used to gain access to the raw MeCab information.
  - $tokens - An array of information gained from the MeCab nodes.
  - $words - An array of LimelightWord objects.
  - $config - An instance of Limelight\Config\Config.
  
To get config data, use the config instance on the parent.
```php
$options = $this->config->get('PluginName');
```

After gaining what data you need, set the data on an individual LimelightWord instance with the setPluginData() method, passing through the name of your plugin and the data.  
```php
$word->setPluginData('PluginName', $data);
```
  
To set data on the LimelightResults object, return the data.
```php
return $data;
```
  
A plugin template with some example code can be found in Limelight/Plugins.  
  
[Top](#contents)
  
## Change Log  
  
Nov. 1, 2015: Version 1.2.0
  - Added Romanji plugin
  - Improved plugin data accessability
  - Bug fixes

Oct. 30, 2015: Version 1.1.0
  - Added plugin ability
  - Added furigana plugin
  - Bug fixes

Oct. 29, 2015: Version 1.0.2
  - Fixed dotenv bugs
  - Fixed config bugs
  - Add Laravel support

Oct. 28, 2015: Version 1.0.1
  - Bug fixes
  - Refactored PartofSpeech classes
  - Travis CI integration

Oct. 27, 2015: Version 1.0
  - Version 1.0 released.

## Sources, Contributions, and Contributing

The Japanese parsing logic used in Limelight was adapted from Kimtaro's excellent Ruby program [Ve](https://github.com/Kimtaro/ve).  A big thank you to him and all the others who contributed on that project.  

Contributors more than welcome.
  
[Top](#contents)
