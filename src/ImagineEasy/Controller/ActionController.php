<?php
namespace ImagineEasy\Controller;

use ImagineEasy\Service;

class ActionController
{
    private $s3;

    private $url;

    public function __construct(Service\S3Service $s3, $browseUrl)
    {
        $this->s3 = $s3;
        $this->url = $browseUrl;
    }

    public function deleteAction($path, $folder = false)
    {
        if (false === $folder) {
            $this->s3->delete($path);

        } else {
            $this->s3->deleteFolder($path);
        }

        $url = dirname($path) . '/';
        return $this->url . '?folder=' . $url;
    }
}
