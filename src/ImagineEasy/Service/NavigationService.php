<?php
namespace ImagineEasy\Service;

class NavigationService
{
    private $delimiter = '/';

    private $nav = [];

    private $path;

    public function setPath($path)
    {
        $this->path = $path;

        if (!empty($this->path)) {
            $parts = explode($this->delimiter, $this->path);
            $folder = '';

            foreach ($parts as $part) {

                $part = trim($part);
                if (empty($part)) {
                    continue;
                }

                $folder .= $part . '/';

                $this->nav[] = [
                    'folder' => $folder,
                    'name' => $part,
                ];
            }

        }
    }

    public function get()
    {
        return $this->nav;
    }
}
