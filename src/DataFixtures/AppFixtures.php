<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
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

        $users = [];

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

            // Rajoute le 'user' au tableau 'users' pour la génération des commandes
            $users[] = $user;

            $manager->persist($user);
        }

        $products = [];

        // Créé des catégories en plus de produits correspondant a chaqune de celle ci
        for ($c = 0;$c < 3;$c++){
            $category = new Category;
            $category->setName($faker->name());
            $manager->persist($category);

            for($p = 0;$p < 5;$p++){
                $product = new Product;
                $product->setName($faker->name())
                ->setPrice(mt_rand(4000, 20000))
                // mis dans un evenement doctrine
                // ->setSlug(strtolower($this->slugger->slug($product->getName())))
                ->setCategory($category)
                ->setShortDescription($faker->paragraph())
                ->setMainPicture($faker->imageUrl(400, 400, true));

                $products[] = $product;
                $manager->persist($product);
            }
        }

        // Créé des commandes 
        for ($p = 0;$p < 30;$p++) {
            
            $purchase = new Purchase;
            $purchase->setFullName($faker->name)
                ->setAddress($faker->streetAddress)
                ->setPostalCode($faker->postcode)
                ->setCity($faker->city)
                ->setUser($faker->randomElement($users))
                ->setTotal(mt_rand(2000, 30000))
                ->setPurchasedAt($faker->dateTimeBetween('-6 months'));


            $selectedProducts = $faker->randomElements($products, mt_rand(2, 5));

            foreach($selectedProducts as $product) {
                $purchaseItem = new PurchaseItem;
                $purchaseItem->setProduct($product)
                ->setQuantity(mt_rand(1, 3))
                ->setProductName($product->getName())
                ->setProductPrice($product->getPrice())
                ->setTotal($purchaseItem->getProductPrice() * $purchaseItem->getQuantity())
                ->setPurchase($purchase);

                $manager->persist($purchaseItem);
            }

            if ($faker->boolean(85)) {
                $purchase->setStatus(Purchase::STATUS_PAID);
            }

            $manager->persist($purchase);
        }

        $manager->flush();
    }
}
