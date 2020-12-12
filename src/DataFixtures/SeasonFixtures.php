<?php


namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i <50 ; $i++) {
            $season = new Season();
            $season->setNumber($i +1 );
            $season->setYear(rand(1994, 2019));
            $season->setDescription($faker->text);
            $season->setProgram($this->getReference('program_' . rand(0,5)));
            $this->addReference('season_' . $i, $season);
            $manager->persist($season);

        }
        $manager->flush();
    }

}
