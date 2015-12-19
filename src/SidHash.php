<?php

namespace Dnoegel\SidInfo;

class SidHash
{
    const HEADER_SIZE_V1 = 118;
    const HEADER_SIZE_V2 = 124;
    const LOAD_ADDRESS_SIZE = 2;

    const CIA_SPEED = 60;
    const VBL_SPPED = 0;
    const NTSC_CLOCK = 2;


    /**
     * Calculates the md5 of a given (sid) file handle corresponding to the
     * songlength database hash definition.
     * The songlength database will not take into account some meta data,
     * so this md5 hash is quite specific
     *
     * @param $fh
     * @return string
     */
    public function calculate($fh)
    {
        $isNtsc = false;
        $isPsidSpecific = false;

        $hashString = '';

        $dataOffset = ord($this->seekAndRead($fh, 6, 1)) << 8 | ord($this->seekAndRead($fh, 7, 1));
        $loadAddress = ord($this->seekAndRead($fh, 8, 1)) << 8 | ord($this->seekAndRead($fh, 9, 1));
        $numSongs = ord($this->seekAndRead($fh, 14, 1)) << 8 | ord($this->seekAndRead($fh, 15, 1));

        $isRsid = $this->seekAndRead($fh, 0, 4) == 'RSID';

        $speed = 4294967295;
        if (!$isRsid) {
            $speed = ord($this->seekAndRead($fh, 18, 1)) << 24 | ord($this->seekAndRead($fh, 19, 1)) << 16 | ord($this->seekAndRead($fh, 20, 1)) << 8 | ord($this->seekAndRead($fh, 21, 1));
        }

        if ($dataOffset == self::HEADER_SIZE_V2) {
            $isNtsc = ord($this->seekAndRead($fh, 119, 1) & 12) == 8;
            if (!$isRsid) {
                $isPsidSpecific = ord($this->seekAndRead($fh, 119, 1) & 2) > 0;
            }
        } elseif ($dataOffset != self::HEADER_SIZE_V1) {
            throw new \RuntimeException("Wrong header size");
        }

        if ($loadAddress == 0) {
            $dataOffset += self::LOAD_ADDRESS_SIZE;
        }

        fseek($fh, 0, SEEK_END);

        $hashString .= ($this->seekAndRead($fh, $dataOffset, ftell($fh) - $dataOffset));
        $hashString .= ($this->seekAndRead($fh, 11, 1));
        $hashString .= ($this->seekAndRead($fh, 10, 1));
        $hashString .= ($this->seekAndRead($fh, 13, 1));
        $hashString .= ($this->seekAndRead($fh, 12, 1));
        $hashString .= ($this->seekAndRead($fh, 15, 1));
        $hashString .= ($this->seekAndRead($fh, 14, 1));

        foreach (range(0, $numSongs - 1) as $song) {
            if (!$isPsidSpecific) {
                if ($song < 31) {
                    $VbiSpeed = !($speed & (1 << $song));
                } else {
                    $VbiSpeed = !($speed & (1 << 31));
                }
            } else {
                $VbiSpeed = !($speed & (1 << ($song % 32)));
            }

            if ($VbiSpeed) {
                $hashString .= (chr(self::VBL_SPPED));
            } else {
                $hashString .= (chr(self::CIA_SPEED));
            }
        }

        if ($isNtsc) {
            $hashString .= (chr(self::NTSC_CLOCK));
        }

        return md5($hashString);
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