<?php


class SidTest extends PHPUnit_Framework_TestCase
{
    public function testSidInfo()
    {
        $p = getenv('HVSC_PATH') . '/MUSICIANS/W/Welle_Erdball/23.sid';
        $hash = '83d386cb4ba73969a031a0b59079ccfa';

        $parser = new Dnoegel\SidInfo\SidParse();
        $struct = $parser->parseFile($p);

        $this->assertEquals($hash, $struct->getHash());
        $this->assertEquals('23', $struct->getTitle());
        $this->assertEquals('Hannes Malecki (Honey)', $struct->getArtist());
        $this->assertEquals('2001 Welle: Erdball', $struct->getCopyright());
        $this->assertEquals(11346, $struct->getSize());
        $this->assertEquals(1, $struct->getStartSong());
        $this->assertEquals(1, $struct->getSubSong());
        $this->assertEquals('PSID', $struct->getType());
        $this->assertEquals(2, $struct->getVersion());
    }
}