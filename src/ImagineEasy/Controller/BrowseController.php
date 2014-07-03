<?php
namespace ImagineEasy\Controller;

use ImagineEasy\Service;
use Twig_Environment;

class BrowseController
{
    /**
     * @var Service\S3Service
     */
    private $s3;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(
        Service\S3Service $aws,
        Service\NavigationService $nav,
        \Twig_Environment $twig)
    {
        $this->s3 = $aws;
        $this->nav = $nav;
        $this->twig = $twig;
    }

    public function indexAction($folder = null)
    {
        $objects = $this->s3->show($folder);
        $this->nav->setPath($folder);

        return $this->twig->render(
            'folders.html',
            [
                'nav' => $this->nav->get(),
                'objects' => $objects,
            ]
        );
    }

    public function showAction($file)
    {
        if (empty($file)) {
            throw new \InvalidArgumentException("Missing file.");
        }

        $url = $this->s3->url($file);
        return $url;
    }
}
