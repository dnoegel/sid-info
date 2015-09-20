<?php

namespace Dnoegel\SidInfo\Struct;

class SidInfo
{
    private $type;
    private $version;
    private $subSong;
    private $startSong;
    private $title;
    private $artist;
    private $copyright;
    private $size;
    private $hash;

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return mixed
     */
    public function getSubSong()
    {
        return $this->subSong;
    }

    /**
     * @return mixed
     */
    public function getStartSong()
    {
        return $this->startSong;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @return mixed
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }


}