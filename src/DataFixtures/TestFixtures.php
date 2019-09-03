<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Horse;
use App\Entity\ApiUser;

class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $horse = new Horse();
            $horse
                ->setName('Testhorse ' . (string)$i)
                ->setPicture('http://test.tst/test' . (string)$i . '.jpg');
            $manager->persist($horse);
        }

        $apiUser = new ApiUser();
        $apiUser
            ->setName('TEST00000')
            ->setApiToken('TEST00000')
            ->setPassword('');
        $manager->persist($apiUser);

        $manager->flush();
    }
}
