<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $slugger = new AsciiSlugger();
        foreach (AbstractDataFixtures::CATEGORIES as $main => $sub) {

            $mainCategory = new Category();
            $mainCategory
                ->setName($main)
                ->setSlug($slugger->slug($main));
            $manager->persist($mainCategory);

            foreach ($sub as $subCat) {

                $subCategory = new Category();
                $subCategory
                    ->setName($subCat)
                    ->setSlug($slugger->slug($subCat))
                    ->setParent($mainCategory);;
                $manager->persist($subCategory);

                $this->addReference("subcategory$subCat", $subCategory);
            }
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
