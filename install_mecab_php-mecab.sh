#!/bin/sh
set -ex
sudo apt-get install mecab mecab-ipadic-utf8
wget https://github.com/rsky/php-mecab/archive/master.zip
unzip master.zip
cd php-mecab-master/mecab && phpize && ./configure --with-php-config=/usr/bin/php-config --with-mecab-config=/usr/bin/mecab-config && make && sudo make install
cd /etc/php5/mods-available/
sudo touch mecab.ini
echo "extension=mecab.so" | sudo tee -a mecab.ini
sudo php5enmod mecab