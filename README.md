# Limelight   
[![Latest Stable Version](https://poser.pugx.org/nihongodera/limelight/version.svg)](//packagist.org/packages/nihongodera/limelight) 
[![License](https://poser.pugx.org/nihongodera/limelight/license.svg)](//packagist.org/packages/nihongodera/limelight)  
##### A php Japanese language analyzer and parser.  
  - Split Japanese text into individual, full words
  - Find parts of speech for words
  - Find dictionary entries (lemmas) for conjugated words
  - Get readings and pronunciations for words
  - Build fuirgana for words
  - Convert Japanese to romaji (English lettering)

### Quick Guide
  - [Version Notes](#version-notes)
  - [Install Limelight](#install-limelight)
  - [Parse Text](#parse-text)
  - [Get Results](#get-results)
  - [Full Documentation](#full-documentation)
  - [Sources, Contributions, and Contributing](#sources-contributions-and-contributing)

### Version Notes
  - April 25, 2016: The Limelight API changed in Version 1.6.0. The new API uses collection methods to give developers better control of Limelight parse results. Please see the [wiki](https://github.com/nihongodera/limelight/wiki) for the updated documentation.
  - April 11, 2016: php-mecab, the MeCab bindings Limelight uses, were updated to version 0.6.0 in Dec. 2015 for php 7 support. The pre-0.6.0 bindings no longer work with the master branch of Limelight. If you are using an older version of php-mecab, please update your bindings or use the [php-mecab_pre_0.6.0](https://github.com/nihongodera/limelight/tree/php-mecab_pre_0.6.0) version.

### Install Limelight
##### Requirements
  - php > 5.6

##### Dependencies
Before installing Limelight, you must install both mecab and the php extension php-mecab on your system.   

###### Linux Ubuntu Users
Use the install script included in this repository. The script only works for and php7.
Download the script:
```
curl -O https://raw.githubusercontent.com/nihongodera/limelight/master/install_mecab_php-mecab.sh
```
Make the file executable:
```
chmod +x install_mecab_php-mecab.sh
```
Execute the script:
```
./install_mecab_php-mecab.sh
```
You may need to restart your server to complete the process.  
    
For information about what the script does, see [here](https://github.com/nihongodera/limelight/wiki/Install-Script).

###### Other Systems

Please see [this page](https://github.com/nihongodera/php-mecab-documentation) to learn more about installing on your system.   
    
##### Install Limelight
Install Limelight through composer.
```
composer require nihongodera/limelight
```

### Parse Text
Make a new instance of Limelight\Limelight.  Limelight takes no arguments.
```php
$limelight = new Limelight();
```

Use the parse() method on the Limelight object to parse Japanese text.
```php
$results = $limelight->parse('庭でライムを育てています。');
```
The returned object is an instance of Limelight\Classes\LimelightResults.

### Get Results
Get results for the entire text using methods available on [LimelightResults](https://github.com/nihongodera/limelight/wiki/LimelightResults).
```php
$results = $limelight->parse('庭でライムを育てています。');

echo 'Words: ' . $results->string('word') . "\n";
echo 'Readings: ' . $results->string('reading') . "\n";
echo 'Pronunciations: ' . $results->string('pronunciation') . "\n";
echo 'Lemmas: ' . $results->string('lemma') . "\n";
echo 'Parts of speech: ' . $results->string('partOfSpeech') . "\n";
echo 'Hiragana: ' . $results->toHiragana()->string('word') . "\n";
echo 'Katakana: ' . $results->toKatakana()->string('word') . "\n";
echo 'Romaji: ' . $results->string('romaji', ' ') . "\n";
echo 'Furigana: ' . $results->string('furigana') . "\n";
```
> **Output:**    
> Words: 庭でライムを育てています。   
> Readings: ニワデライムヲソダテテイマス。   
> Pronunciations: ニワデライムヲソダテテイマス。   
> Lemmas: 庭でライムを育てる。   
> Parts of speech: noun postposition noun postposition verb symbol   
> Hiragana: にわでらいむをそだてています。   
> Katakana: ニワデライムヲソダテテイマス。  
> Romaji: niwa de raimu o sodateteimasu.   
> Furigana: <ruby><rb>庭</rb><rp>(</rp><rt>にわ</rt><rp>)</rp></ruby>でライムを<ruby><rb>育</rb><rp>(</rp><rt>そだ</rt><rp>)</rp></ruby>てています。   
       
Alter the collection of words however you like using the library of [collection methods](https://github.com/nihongodera/limelight/wiki/Collection-Methods).
     
Get individual words off the LimelightResults object by using one of several applicable [collection methods](https://github.com/nihongodera/limelight/wiki/Collection-Methods). Use methods available on the returned [LimelightWord](https://github.com/nihongodera/limelight/wiki/LimelightWord) object.
```php
$results = $limelight->parse('庭でライムを育てています。');

$word1 = $results->pull(2);

$word2 = $results->where('word', '庭');

echo $word1->string('romaji') . "\n";

echo $word2->string('furigana') . "\n";
```
> **Output:**  
> raimu   
> <ruby>庭<rt>にわ</rt></ruby>   
   
Methods on the LimelightResults object and the LimelightWord object follow the same conventions, but LimelightResults methods are plural (word**s**()) while LimelightWord methods are singular (word()).
  
Alternatively, loop through all the words on the LimelightResults object.
```php
$results = $limelight->parse('庭でライムを育てています。');

foreach ($results as $word) {
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
     
Collection methods and methods in the Arr class were derived from Laravel's [collection](https://github.com/illuminate/support/blob/master/Collection.php) methods.
    
Contributors more than welcome.
  
[Top](#contents)
