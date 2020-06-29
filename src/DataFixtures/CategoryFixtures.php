<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Self_;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Action' => '#ffb300',
        'Aventure' => '#00897b',
        'Romance' => '#d81b60',
        'Fantastique' => '#424242',
        'Horreur' => '#d84315',
    ];

    public function load(ObjectManager $manager)
    {
        $index = 0;
        foreach (Self::CATEGORIES as $key => $data){
            $category = new Category();
            $category->setName($key);
            $category->setColor($data);
            $this->addReference('categorie_' . $index, $category);
            $manager->persist($category);
            $index ++;
        }
        $manager->flush();
    }
}
