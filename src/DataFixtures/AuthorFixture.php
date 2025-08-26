<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AuthorFixture extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $author = new Author();
            $author->setName($faker->name);
            $manager->persist($author);
        }
        $manager->flush();
    }
}