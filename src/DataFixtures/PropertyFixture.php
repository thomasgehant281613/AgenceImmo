<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i = 0; $i < 100; $i++){
            $property = new Property();
            $property
                ->setTitle($faker->words(6,true))
                ->setDescription($faker->sentences(3,true))
                ->setSurface($faker->numberBetween(20,450))
                ->setRooms($faker->numberBetween(2,12))
                ->setBedrooms($faker->numberBetween(1,7))
                ->setFloor($faker->numberBetween(0,10))
                ->setPrice($faker->numberBetween(35000,1500000))
                ->setHeat($faker->numberBetween(0,count(Property::HEAT) -1 ))
                ->setCity($faker->city)
                ->setAddress($faker->address)
                ->setPostalCode($faker->postcode)
                ->setSold(false);
            $manager->persist($property);
        }
        // $product = new Product();
        $manager->flush();
    }
}
