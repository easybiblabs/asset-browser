<?php
namespace ImagineEasy\Service;

use Aws\S3\S3Client;
use ImagineEasy\Collection;

class S3Service
{
    private $bucket;

    private $s3;

    public function __construct(S3Client $s3, $bucket)
    {
        $this->s3 = $s3;
        $this->bucket = $bucket;
    }

    public function delete($path)
    {
        return $this->s3->deleteObject(
            [
                'Bucket' => $this->bucket,
                'Key' => $path
            ]
        );
    }

    public function deleteFolder($path)
    {
        $iterator = $this->getObjects($path);

        $objects = [];
        foreach ($iterator as $object) {
            if (!isset($object['Key'])) {
                throw new \DomainException("Recursive delete is not supported.");
            }

            $objects[] = [
                'Key' => $object['Key'],
            ];
        }

        return $this->s3->deleteObjects(
            [
                'Bucket' => $this->bucket,
                'Objects' => $objects,
            ]
        );
    }

    public function show($folder = null)
    {
        $iterator = $this->getObjects($folder);

        $objects = new Collection\FileCollection;
        foreach ($iterator as $object) {
            $objects->add($object);
        }

        return $objects;
    }

    public function url($file)
    {
        $request = $this->s3->get(sprintf('%s/%s', $this->bucket, $file));
        $url = $this->s3->createPresignedUrl($request, '+15 minutes');

        return $url;
    }

    private function getObjects($folder)
    {
        $listOpts = [
            'Bucket' => $this->bucket,
            'Delimiter' => '/',
        ];

        if (null !== $folder) {
            $listOpts['Prefix'] = $folder;
        }

        $iteratorOpts = [
            'names_only' => false,
            'return_prefixes' => true,
        ];

        $iterator = $this->s3->getIterator(
            'ListObjects',
            $listOpts,
            $iteratorOpts
        );

        return $iterator;
    }
}
