# Sid info
Provides information about (C64) SID files. Reads binary .sid files and supports the
HVSC song length database.

# Parsing a sid file
```
$parser = new Dnoegel\SidInfo\SidParse();
$struct = $parser->parseFile('/home/to/sid/MUSICIANS/W/Welle_Erdball/23.sid');
```

will return an object like this:

```
Dnoegel\SidInfo\Struct\SidInfo Object
(
    [type:Dnoegel\SidInfo\Struct\SidInfo:private] => PSID
    [version:Dnoegel\SidInfo\Struct\SidInfo:private] => 2
    [subSong:Dnoegel\SidInfo\Struct\SidInfo:private] => 1
    [startSong:Dnoegel\SidInfo\Struct\SidInfo:private] => 1
    [title:Dnoegel\SidInfo\Struct\SidInfo:private] => 23
    [artist:Dnoegel\SidInfo\Struct\SidInfo:private] => Hannes Malecki (Honey)
    [copyright:Dnoegel\SidInfo\Struct\SidInfo:private] => 2001 Welle: Erdball
    [size:Dnoegel\SidInfo\Struct\SidInfo:private] => 11346
    [hash:Dnoegel\SidInfo\Struct\SidInfo:private] => 83d386cb4ba73969a031a0b59079ccfa
)
```

# The song length database

This library also supports the song length database of the HVSC sid collection:

```
$database = new \Dnoegel\SidInfo\SongLengthDatabase('/home/to/sid/DOCUMENTS/Songlengths.txt');

$result = $database->find('83d386cb4ba73969a031a0b59079ccfa');
```

Will return an array of song length information - one element for each (sub)song of the tune.

Please be aware, that reading the song length database each time might be quite some overhead.
It might be more reasonable to read the database once and export all song information to e.g.
a mysql database.

# Calculating SID hashes

Even though this is more an internal component: If you need to calculate the MD5 hash of a SID
file in the same way, it is done for the song length database, you can use the `SidHash`
class:

```
$hashCalculator = new \Dnoegel\SidInfo\SidHash();
$hash = $hashCalculator->calculate($pathToSid)
```

The returned `hash` can be looked up in the song length database and hashes the SID file without
meta-data.