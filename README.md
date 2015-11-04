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
  - Convert Japanese to romanji (English lettering)

### Quick Guide
  - [Install limelight](#install-limelight)
  - [Initialize limelight](#initialize-limelight)
  - [Parse Text](#parse-text)
  - [Get Results](#get-results)
  - [Full Documentation](#full-documentation)
  - [Sources, Contributions, and Contributing](#sources-contributions-and-contributing)

### Install Limelight
[Install MeCab and the php extension php-mecab](https://github.com/nihongodera/php-mecab-documentation) on your system.  
  
Install Limelight through composer.
```
composer require nihongodera/limelight
```

### Initialize Limelight
Make a new instance of Limelight\Limelight.  Limelight takes no arguments.
```php
$limelight = new Limelight();
```

### Parse Text
Use the parse() method on the Limelight object to parse Japanese text.
```php
$results = $limelight->parse('庭でライムを育てています。');
```
The returned object is an instance of Limelight\Classes\LimelightResults.

### Get Results
Get results for the entire text using methods available on [LimelightResults](https://github.com/nihongodera/limelight/wiki/LimelightResults).
```php
$results = $limelight->parse('庭でライムを育てています。');

echo 'Words: ' . $results->words() . "\n";
echo 'Readings: ' . $results->readings() . "\n";
echo 'Pronunciations: ' . $results->pronunciations() . "\n";
echo 'Lemmas: ' . $results->lemmas() . "\n";
echo 'Parts of speech: ' . $results->partsOfSpeech() . "\n";
echo 'Hiragana: ' . $results->toHiragana()->words() . "\n";
echo 'Katakana: ' . $results->toKatakana()->words() . "\n";
echo 'Romanji: ' . $results->toRomanji()->words() . "\n";
echo 'Furigana: ' . $results->toFurigana()->words() . "\n";
```
> **Output:**    
> Words: 庭でライムを育てています。   
> Readings: ニワデライムヲソダテテイマス。   
> Pronunciations: ニワデライムヲソダテテイマス。   
> Lemmas: 庭でライムを育てる。   
> Parts of speech: noun postposition noun postposition verb symbol   
> Hiragana: にわでらいむをそだてています。   
> Katakana: ニワデライムヲソダテテイマス。  
> Romanji: Niwa de raimu o sodateteimasu.   
> Furigana: <ruby>庭<rt>にわ</rt></ruby>でライムを<ruby>育<rt>そだ</rt></ruby>てています。   
   
Get individual words off the LimelightResults object by selecting them by either word or index and using methods availabel on the returned [LimelightWord](https://github.com/nihongodera/limelight/wiki/LimelightWord) object.
```php
$results = $limelight->parse('庭でライムを育てています。');

$word1 = $results->findIndex(2);

$word2 = $results->findWord('庭');

echo $word1->toRomanji()->word() . "\n";

echo $word2->toFurigana()->word() . "\n";
```
> **Output:**  
> raimu   
> <ruby>庭<rt>にわ</rt></ruby>   
   
Notice that methods on the LimelightResults object and the LimelightWord object follow the same conventions, but LimelightResults methods are plural (word**s**()) while LimelightWord methods are singular (word()).
  
Alternatively, loop through all the words on the LimelightResults object using the next() method.
```php
$results = $limelight->parse('庭でライムを育てています。');

foreach ($results->next() as $word) {
    echo $word->word() . ' is a ' . $word->partOfSpeech() . ' read like ' . $word->reading() . "\n";
}
```
> **Output:**    
> 庭 is a noun read like ニワ   
> で is a postposition read like デ   
> ライム is a noun read like ライム   
> を is a postposition read like ヲ   
> 育てています is a verb read like ソダテテイマス   
> 。 is a symbol read like 。   
   
### Full Documentation

Full documentation for Limelight can be found on the [Limelight Wiki page](https://github.com/nihongodera/limelight/wiki).

### Sources, Contributions, and Contributing

The Japanese parsing logic used in Limelight was adapted from Kimtaro's excellent Ruby program [Ve](https://github.com/Kimtaro/ve).  A big thank you to him and all the others who contributed on that project. 
   
Limelight relies heavily on both [MeCab](http://taku910.github.io/mecab/) and [php-mecab](https://github.com/rsky/php-mecab).

Contributors more than welcome.
  
[Top](#contents)
