# Limelight   
[![Build Status](https://travis-ci.org/nihongodera/limelight.svg?branch=master)](https://travis-ci.org/zachleigh/petrol)
[![Latest Stable Version](https://poser.pugx.org/nihongodera/limelight/version.svg)](//packagist.org/packages/zachleigh/petrol) 
[![License](https://poser.pugx.org/nihongodera/limelight/license.svg)](//packagist.org/packages/zachleigh/petrol)  
##### A php Japanese language analyzer and parser.  
  - Split Japanese text into individual, full words
  - Find parts of speech for words
  - Find dictionary entries (lemmas) for conjugated words
  - Get readings and pronunciations for words

## Contents  
  - [Quick Guide](#quick-guide)
  - [Installation](#installation)
  - [Usage](#usage)
    - [Parsing Strings](#parsing-strings)
    - [Using the LimelightResults Class](#using-the-limelightresults-class)
    - [Using the LimelightWord Class](#using-the-limelightword-class)
    - [Doing Raw MeCab Queries](#doing-raw-mecab-queries)
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
echo $wordObject->reading;  // Output: おいしい

echo $wordObject->reading()->toKatakana()->get(); // Output: オイシイ
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
  
## Change Log  

Oct. 28, 2015: v.1.0.1
  - Bug fixes
  - Refactor PartofSpeech classes
  - Travis CI integration

Oct. 27, 2015: v1.0
  - Version 1.0 released.

## Sources, Contributions, and Contributing

The Japanese parsing logic used in Limelight was adapted from Kimtaro's excellent Ruby program [Ve](https://github.com/Kimtaro/ve).  A big thank you to him and all the others who contributed on that project.  

Contributors more than welcome.
  
[Top](#contents)
