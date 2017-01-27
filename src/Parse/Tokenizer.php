<?php

namespace Limelight\Parse;

use Limelight\Mecab\Node;

class Tokenizer
{
    /**
     * @var array
     */
    private $tokens = [];

    /**
     * If true, EOS has not yet been found.
     *
     * @var bool
     */
    private $parsing = false;

    /**
     * Lookup for Mecab feature parameters.
     *
     * @var array
     */
    private $mecabParameters = [
        'partOfSpeech1',
        'partOfSpeech2',
        'partOfSpeech3',
        'partOfSpeech4',
        'inflectionType',
        'inflectionForm',
        'lemma',
        'reading',
        'pronunciation',
    ];

    /**
     * Coversions to convert mecab output to english.
     *
     * @var array
     */
    private $conversions = [
        // Part of speech
        '名詞'   => 'meishi',
        '固有名詞' => 'koyuumeishi',
        '代名詞'  => 'daimeishi',
        '助動詞'  => 'jodoushi',
        '数'    => 'kazu',
        '助詞'   => 'joshi',
        '接頭詞'  => 'settoushi',
        '動詞'   => 'doushi',
        '記号'   => 'kigou',
        'フィラー' => 'firaa',
        'その他'  => 'sonota',
        '感動詞'  => 'kandoushi',
        '連体詞'  => 'rentaishi',
        '接続詞'  => 'setsuzokushi',
        '副詞'   => 'fukushi',
        '接続助詞' => 'setsuzokujoshi',
        '形容詞'  => 'keiyoushi',

        // Secondary part of speech, inflection types
        '非自立'     => 'hijiritsu',
        '副詞可能'    => 'fukushikanou',
        'サ変接続'    => 'sahensetsuzoku',
        '形容動詞語幹'  => 'keiyoudoushigokan',
        'ナイ形容詞語幹' => 'naikeiyoushigokan',
        '助動詞語幹'   => 'jodoushigokan',
        '副詞化'     => 'fukushika',
        '体言接続'    => 'taigensetsuzoku',
        '連体化'     => 'rentaika',
        '特殊'      => 'tokushu',
        '接尾'      => 'setsubi',
        '接続詞的'    => 'setsuzokushiteki',
        '動詞非自立的'  => 'doushihijiritsuteki',
        'サ変・スル'   => 'sahenSuru',
        '特殊・タ'    => 'tokushuTa',
        '特殊・ナイ'   => 'tokushuNai',
        '特殊・タイ'   => 'tokushuTai',
        '特殊・デス'   => 'tokushuDesu',
        '特殊・ダ'    => 'tokushuDa',
        '特殊・マス'   => 'tokushuMasu',
        '特殊・ヌ'    => 'tokushuNu',
        '不変化型'    => 'fuhenkagata',
        '人名'      => 'jinmei',
        '命令ｉ'     => 'meireiI',
        '係助詞'     => 'kakarijoshi',
        '連用形'     => 'rennyoukei',
        '自立'      => 'jiritsu',
    ];

    /**
     * Make tokens for text and store them on the object.
     *
     * @param Node $node
     *
     * @return array
     */
    public function makeTokens(Node $node)
    {
        $this->walkNodes($node, function ($node) {
            $this->parseNode($node);
        });

        return $this->tokens;
    }

    /**
     * Walk down node series.
     *
     * @param Node     $node
     * @param function $callback
     */
    private function walkNodes(Node $node, $callback)
    {
        while (!is_null($node->getNode())) {
            $callback($node);

            $node = $node->getNext();
        }
    }

    /**
     * Get tokens off node.
     *
     * @param Node $node
     */
    private function parseNode(Node $node)
    {
        $token = [];

        $surface = $node->getSurface();

        $feature = $node->getFeature();

        if ($this->isFirstNode($feature)) {
            $this->parsing = true;

            return;
        }

        if ($this->isLastNode($feature)) {
            $this->parsing = false;

            return;
        } else {
            $token['type'] = 'parsed';

            $token['literal'] = $surface;

            $parameters = explode(',', $feature);

            foreach ($parameters as $index => $parameter) {
                if ($parameter) {
                    $token[$this->mecabParameters[$index]] = $this->getParameter($parameter);
                }
            }
        }

        $this->tokens[] = $token;
    }

    /**
     * Return true if feature is from the first node.
     *
     * @param string $feature
     *
     * @return bool
     */
    private function isFirstNode($feature)
    {
        if (strpos($feature, 'BOS') !== false && $this->parsing === false) {
            return true;
        }

        return false;
    }

    /**
     * Return true if feature is from the last node.
     *
     * @param string $feature
     *
     * @return bool
     */
    private function isLastNode($feature)
    {
        if (strpos($feature, 'BOS') !== false && $this->parsing === true) {
            return true;
        }

        return false;
    }

    /**
     * Get parameter conversion or return parameter.
     *
     * @param string $parameter
     *
     * @return string
     */
    private function getParameter($parameter)
    {
        if (array_key_exists($parameter, $this->conversions)) {
            return $this->conversions[$parameter];
        }

        return $parameter;
    }
}
