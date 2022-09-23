<?php

namespace ClaraLeigh\NaughtyUsername;

use ClaraLeigh\NaughtyUsername\Traits\ReadFileToArray;

class StringCheck
{
    use ReadFileToArray;
    public bool $debug = false;
    private array $censorChecks;
    private array $censorChecksOg;
    private array $whitelist = [];
    public array $badwords = [];

    public function __construct(array $black_lists = [], array $white_lists = [])
    {
        if (empty($black_lists)) {
            $black_lists = ['profanity-en', 'reserved-words'];
        }
        if (empty($white_lists)) {
            $white_lists = ['whitelist'];
        }
        $this->addDictionary($black_lists);
        $this->addWhitelist($white_lists);
    }

    public function addDictionary(array $dictionary): void
    {
        $this->badwords = array_merge($this->badwords, $this->readFiles($dictionary));
    }

    public function addWhitelist(array $whitelist): void
    {
        $this->whitelist = array_merge($this->whitelist, $this->readFiles($whitelist));
    }

    private function replaceWhiteListedWords(string $string): string
    {
        foreach ($this->whitelist as $key => $word) {
            $string = str_replace($word, '  ', $string);
        }
        return $string;
    }

    private function generateCensorChecks(): void
    {
        $badwords = $this->badwords;

        $leet_replace      = array();
        $leet_replace['a'] = '(a|a\.|a\-|4|@|Á|á|À|Â|à|Â|â|Ä|ä|Ã|ã|Å|å|α|Δ|Λ|λ)';
        $leet_replace['b'] = '(b|b\.|b\-|8|\|3|ß|Β|β)';
        $leet_replace['c'] = '(c|c\.|c\-|Ç|ç|¢|€|<|\(|{|©)';
        $leet_replace['d'] = '(d|d\.|d\-|&part;|\|\)|Þ|þ|Ð|ð)';
        $leet_replace['e'] = '(e|e\.|e\-|3|€|È|è|É|é|Ê|ê|∑)';
        $leet_replace['f'] = '(f|f\.|f\-|ƒ)';
        $leet_replace['g'] = '(g|g\.|g\-|6|9)';
        $leet_replace['h'] = '(h|h\.|h\-|Η)';
        $leet_replace['i'] = '(i|i\.|i\-|!|\||\]\[|]|1|∫|Ì|Í|Î|Ï|ì|í|î|ï)';
        $leet_replace['j'] = '(j|j\.|j\-)';
        $leet_replace['k'] = '(k|k\.|k\-|Κ|κ)';
        $leet_replace['l'] = '(l|1\.|l\-|!|\||\]\[|]|£|∫|Ì|Í|Î|Ï)';
        $leet_replace['m'] = '(m|m\.|m\-)';
        $leet_replace['n'] = '(n|n\.|n\-|η|Ν|Π)';
        $leet_replace['o'] = '(o|o\.|o\-|0|Ο|ο|Φ|¤|°|ø)';
        $leet_replace['p'] = '(p|p\.|p\-|ρ|Ρ|¶|þ)';
        $leet_replace['q'] = '(q|q\.|q\-)';
        $leet_replace['r'] = '(r|r\.|r\-|®)';
        $leet_replace['s'] = '(s|s\.|s\-|5|\$|§)';
        $leet_replace['t'] = '(t|t\.|t\-|Τ|τ|7)';
        $leet_replace['u'] = '(u|u\.|u\-|υ|µ)';
        $leet_replace['v'] = '(v|v\.|v\-|υ|ν)';
        $leet_replace['w'] = '(w|w\.|w\-|ω|ψ|Ψ)';
        $leet_replace['x'] = '(x|x\.|x\-|Χ|χ)';
        $leet_replace['y'] = '(y|y\.|y\-|¥|γ|ÿ|ý|Ÿ|Ý)';
        $leet_replace['z'] = '(z|z\.|z\-|Ζ)';
        $leet_replace[' '] = '( |\.|\-|_)';

        $censorChecks = array();
        for($i = 0, $max = count($badwords); $i < $max; $i++) {
            $word = $badwords[$i];
            if (strlen($word) <= 3) {
                continue;
            }
            $word = str_ireplace(array_keys($leet_replace), array_values($leet_replace), $word);
            $censorChecks[$i] = '/' . $word . '/i';
            $censorCheckOg[$i] = $badwords[$i];
        }

        $this->censorChecks = $censorChecks;
        $this->censorChecksOg = $censorCheckOg;
    }

    public function validateString(string $string): bool
    {
        if (empty($this->censorChecks)) {
            $this->generateCensorChecks();
        }
        $string = $this->replaceWhiteListedWords($string);
        foreach ($this->censorChecks as $key => $censorCheck) {
            if (preg_match($censorCheck, $string)) {
                if ($this->debug) {
                    echo 'Found: ' . $this->censorChecksOg[$key] . ' in: ' . $string . PHP_EOL;
                }
                return false;
            }
        }
        return true;
    }

}