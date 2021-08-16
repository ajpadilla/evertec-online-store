<?php


namespace Tests\Unit\Repositories;


use App\Models\Product;

class ProductRepositoryTest extends RepositoryTest
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_a_new_product()
    {
        /** @var array */
        $data = $this->makeNewProductData();

        /** @var Product $product*/
        $product = $this->productRepository->create($data);

        $this->assertNotNull($product);
    }

    public function test_search_a_exist_product()
    {
        /** @var array */
        $data = $this->makeNewProductData();

        /** @var Product $product*/
        $product = $this->productRepository->create($data);

        $product = $this->productRepository->find($product->id);

        $this->assertNotNull($product);
    }
}
