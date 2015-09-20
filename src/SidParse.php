<?php

namespace Dnoegel\SidInfo;

use Dnoegel\SidInfo\Struct\SidInfo;

class SidParse
{
    /**
     * @var SidHash
     */
    private $hashCalculator;

    public function __construct()
    {
        $this->hashCalculator = new SidHash();
    }

    /**
     * Parse a given file and return a SidInfo object will all relevant info
     *
     * @param $file
     * @return SidInfo
     */
    public function parseFile($file)
    {
        return $this->parseBinary(fopen($file, 'rb'));
    }

    /**
     * Parse a given fileHandle and return a SidInfo object will all relevant info
     *
     * @param $fh
     * @return SidInfo
     */
    public function parseBinary($fh)
    {
        return new SidInfo([
            'type' => $this->seekAndRead($fh, 0, 4),
            'version' => ord($this->seekAndRead($fh, 5, 1)),
            'subSong' => ord($this->seekAndRead($fh, 15, 1)),
            'startSong' => ord($this->seekAndRead($fh, 17, 1)),
            'title' => trim($this->seekAndRead($fh, 22, 32), "\0"),
            'artist' => trim($this->seekAndRead($fh, 54, 32), "\0"),
            'copyright' => trim($this->seekAndRead($fh, 86, 32), "\0"),
            'size' => fstat($fh)['size'],
            'hash' => $this->hashCalculator->calculate($fh)
        ]);
    }

    /**
     * Helper to combine fseek and fread in one call
     *
     * @param $fh
     * @param $offset
     * @param $length
     * @return string
     */
    private function seekAndRead($fh, $offset, $length)
    {
        fseek($fh, $offset);
        return fread($fh, $length);
    }

}