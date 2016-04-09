#!/bin/sh
set -ex
sudo apt-get install mecab mecab-ipadic-utf8 mecab-utils libmecab-dev unzip build-essential php5-dev
if [ -d /etc/php5/mods-available ] ; then
    wget https://github.com/rsky/php-mecab/archive/master.zip
    unzip master.zip
    cd php-mecab-master/mecab && phpize && ./configure --with-php-config=/usr/bin/php-config --with-mecab-config=/usr/bin/mecab-config && make && sudo make install
    cd /etc/php5/mods-available/
    sudo touch mecab.ini
    echo "extension=mecab.so" | sudo tee -a mecab.ini
    sudo php5enmod mecab
fi