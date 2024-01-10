<?php

// src/DataFixtures/FileFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\File;

class FileFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $filesData = [
            [
                'name' => 'file1',
                'originalName' => 'original_file1.pdf',
                'extension' => 'pdf',
                'size' => 1024,
            ],
            [
                'name' => 'file2',
                'originalName' => 'original_file2.docx',
                'extension' => 'docx',
                'size' => 2048,
            ],
            [
                'name' => 'file3',
                'originalName' => 'original_file3.pdf',
                'extension' => 'pdf',
                'size' => 3072,
            ],
            // Add more files here using the same structure
        ];

        foreach ($filesData as $fileData) {
            $file = new File();
            $file->setName($fileData['name']);
            $file->setOriginalName($fileData['originalName']);
            $file->setExtension($fileData['extension']);
            $file->setSize($fileData['size']);

            $manager->persist($file);
        }

        $manager->flush();
    }
}
