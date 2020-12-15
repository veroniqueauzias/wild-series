<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i < 150; $i++) {
            $episode = new Episode();
            $episode->setTitle($faker->word);
            $slug = $this->slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
            $episode->setNumber($i +1 );
            $episode->setSynopsis($faker->text);
            $episode->setSeason($this->getReference('season_' . rand(0, 49)));
            $manager->persist($episode);

        }
        $manager->flush();
    }

}
