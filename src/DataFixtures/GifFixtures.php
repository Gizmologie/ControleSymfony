<?php

namespace App\DataFixtures;

use App\Entity\Gif;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class GifFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies():array
    {
        return [
            CategoryFixtures::class,
        ];
    }


    public function load(ObjectManager $manager)
    {
        $slugger = new AsciiSlugger();
        foreach (AbstractDataFixtures::CATEGORIES as $category => $subCategories) {
            foreach ($subCategories as $subCategory) {
               $gif = new Gif();
               $gif
                   ->setSource($slugger->slug($subCategory)->lower() . '.gif')
                   ->setSlug($slugger->slug($subCategory)->lower())
                   ->setCategory($this->getReference("subcategory$subCategory"))
                   ->setUser($this->getReference("user"));
               ;

               $manager->persist($gif);
            };
        }
        // $product = new Product();
        // $manager->persist($product);
        $manager->flush();
    }



}
