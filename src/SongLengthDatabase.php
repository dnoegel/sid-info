<?php

namespace Dnoegel\SidInfo;

class SongLengthDatabase
{
    private $databaseFile;

    private $hashToTime;
    private $nameToTime;

    public function __construct($database)
    {
        $this->databaseFile = $database;
    }

    /**
     * Will return an array of all the length information for all subtunes
     * of the given (sid) hash
     *
     * @param $hash
     * @return string[]
     */
    public function find($hash)
    {
        $this->readDatabase();

        if (!array_key_exists($hash, $this->hashToTime)) {
            throw new \RuntimeException("No entry found for {$hash}");
        }

        return $this->hashToTime[$hash];
    }

    /**
     * Will return an array of all the length information for all subtunes
     * of the given (sid) name.
     *
     * @param $name string E.g. /MUSICIANS/W/Welle_Erdball/23.sid
     * @return string[]
     */
    public function findByName($name)
    {
        $this->readDatabase();

        if (!array_key_exists($name, $this->nameToTime)) {
            throw new \RuntimeException("No entry found for {$name}");
        }

        return $this->nameToTime[$name];
    }


    /**
     * Will return the songlength database in a format like this:
     *
     * [
     *    'name1' => ['1:02', '3:04'],
     *    'name2' => ['5:01'],
     *    'name3' => ['1:01', '2:02', '3:04']
     * ]
     *
     * @return string[]
     */
    public function dumpNames()
    {
        return $this->nameToTime;
    }

    /**
     * Will return the songlength database in a format like this:
     *
     * [
     *    'hash1' => ['1:02', '3:04'],
     *    'hash2' => ['5:01'],
     *    'hash3' => ['1:01', '2:02', '3:04']
     * ]
     *
     * @return string[]
     */
    public function dumpHashes()
    {
        return $this->hashToTime;
    }

    /**
     * Parses the song length database and stores internal structures
     * to look up SIDs by name and hash
     */
    private function readDatabase()
    {
        if ($this->hashToTime) {
            return;
        }

        $content = file_get_contents($this->databaseFile);
        $regEx = '#;\s*(?P<name>.+?)\s*(?P<hash>[A-F0-9]+?)=(?P<times>.+?)\s*$#msi';
        $result = [];
        preg_match_all($regEx, $content, $result);

        $result['times'] = array_map(function ($row) {
            return explode(' ', $row);
        }, $result['times']);

        $this->hashToTime = array_combine($result['hash'], $result['times']);
        $this->nameToTime = array_combine($result['name'], $result['times']);
    }

}