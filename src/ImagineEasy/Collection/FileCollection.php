<?php
namespace ImagineEasy\Collection;

class FileCollection implements \Iterator
{
    private $collection = [];

    public function add(array $file)
    {
        $entry = [];
        if (isset($file['Prefix'])) {
            $entry['directory'] = true;
            $entry['name'] = $file['Prefix'];
            $entry['pr'] = '';
            $entry['travis-ci'] = '';
        } else {
            $entry['directory'] = false;
            $entry['name'] = $file['Key'];
        }
        $this->collection[] = $entry;
    }

    public function current()
    {
        return current($this->collection);
    }

    public function key()
    {
        return key($this->collection);
    }

    public function next()
    {
        return next($this->collection);
    }

    public function rewind()
    {
        reset($this->collection);
    }

    public function valid()
    {
        return $this->current() !== false;
    }
}
