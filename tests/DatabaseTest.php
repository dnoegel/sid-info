<?php

class DatabaseTest extends PHPUnit_Framework_TestCase
{
    public function testHash()
    {
        $hash = '83d386cb4ba73969a031a0b59079ccfa';
        $time = '3:02';

        $database = new \Dnoegel\SidInfo\SongLengthDatabase(getenv('HVSC_PATH') . '/DOCUMENTS/Songlengths.txt');

        $result = $database->find($hash);
        $this->assertEquals($time, $result[0]);
    }

    public function testName()
    {
        $relativeName = '/MUSICIANS/W/Welle_Erdball/23.sid';
        $time = '3:02';

        $database = new \Dnoegel\SidInfo\SongLengthDatabase(getenv('HVSC_PATH') . '/DOCUMENTS/Songlengths.txt');

        $result = $database->findByName($relativeName);
        $this->assertEquals($time, $result[0]);
    }
}