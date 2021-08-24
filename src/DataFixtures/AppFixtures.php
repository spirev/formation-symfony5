<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $encoder;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        // Créé un amdin
        $admin = new User();

        $hash = $this->encoder->encodePassword($admin, "password");
        
        $admin->setEmail("admin@gmail.com")
        ->setFullName("Admin")
        ->setPassword($hash)
        ->setRoles(['ROLE_ADMIN']);
        
        $manager->persist($admin);
        
        // Créé des utilisateurs
        for($u = 0;$u < 5;$u++) {
            $user = new User();

            $hash = $this->encoder->encodePassword($user, "password");
            
            $user->setEmail("user$u@gmail.com")
                 ->setFullName($faker->name())
                 ->setPassword($hash);

            $manager->persist($user);
        }

        // Créé des catégories en plus de produits correspondant a chaqune de celle ci
        for ($c = 0;$c < 3;$c++){
            $category = new Category;
            $category->setName($faker->name())
            ->setSlug(strtolower($this->slugger->slug($category->getName())));
            $manager->persist($category);

            for($p = 0;$p < 5;$p++){
                $product = new Product;
                $product->setName($faker->name())
                ->setPrice(mt_rand(4000, 20000))
                ->setSlug(strtolower($this->slugger->slug($product->getName())))
                ->setCategory($category)
                ->setShortDescription($faker->paragraph())
                ->setMainPicture($faker->imageUrl(400, 400, true));
                $manager->persist($product);
            }
        }
        $manager->flush();
    }
}
