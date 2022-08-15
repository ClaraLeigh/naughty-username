<?php

namespace ClaraLeigh\NaughtyUsername\Traits;

trait ReadFileToArray
{
    private function readFiles(array $dictionary): array
    {
        $words = [];
        foreach ($dictionary as $dictionary_file) {
            $words[] = $this->readFile($dictionary_file);
        }

        $words = array_merge(...$words);

        // Remove duplicates
        return array_keys(array_count_values($words));
    }

    private function readFile(string $dictionary): array
    {
        $baseDictPath = __DIR__ . '/../Dict/';

        if (file_exists($baseDictPath . $dictionary . '.js')) {
            $string = file_get_contents($baseDictPath . $dictionary . '.js');
        } elseif (file_exists($dictionary)) {
            $string = file_get_contents($dictionary);
        } else {
            throw new \RuntimeException('Dictionary not found: '.$dictionary);
        }
        // Decode the JSON into an array
        $words = json_decode($string, true);

        return array_keys(array_count_values($words));
    }
}