<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i < 600; $i++) {
            $episode = new Episode();
            $episode->setTitle($faker->sentence);
            $episode->setNumber($i%10 +1 );
            $episode->setSynopsis($faker->text);
            $episode->setSeason($this->getReference('season_' . floor($i/10)));
            $manager->persist($episode);

        }
        $manager->flush();
    }

}
