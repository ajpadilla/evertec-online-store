<?php

namespace Database\Seeders;

use App\Repositories\RepositoryInterface\ProductRepositoryInterface;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;
class ProductsSeeder extends Seeder
{
    /** @var Generator  */
    private $faker;

    /**
     * ProductsSeeder constructor.
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var ProductRepositoryInterface $productRepository */
        $productRepository = app(ProductRepositoryInterface::class);

        for ($i = 0; $i <= 10; $i++)
        {
            $productRepository->create([
                'name' => $this->faker->text($maxNbChars = 10),
                'price' =>  $this->faker->numberBetween($min = 10000, $max = 20000)
            ]);
        }
    }

    /**
     * @return object
     * @throws BindingResolutionException
     */
    protected function withFaker(): object
    {
        return Container::getInstance()->make(Generator::class);
    }
}
