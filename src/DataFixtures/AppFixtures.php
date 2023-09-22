<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Client;
use App\ValueObject\ClientId;
use App\ValueObject\ClientName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 3; $i++) {
            $clientId = ClientId::next();
            /** @var ClientId $clientId */
            $client = new Client(
                $clientId,
                new ClientName($faker->firstName, $faker->lastName)
            );
            $manager->persist($client);
        }

        $manager->flush();
    }
}
