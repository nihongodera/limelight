#!/bin/sh
set -ex
wget https://github.com/rsky/php-mecab/archive/master.zip
unzip master.zip
cd php-mecab-master/mecab && phpize && ./configure --with-php-config=/usr/bin/php-config --with-mecab-config=/usr/bin/mecab-config && make && sudo make install
echo "extension=<extension>.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini