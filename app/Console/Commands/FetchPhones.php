<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use App\Models\Phone;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductTag;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Log;

class FetchPhones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:phones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get('https://dummyjson.com/products')->json();
        $response = $response['products'];

        DB::transaction(function() use ($response) {
            foreach($response as $product) {

                Log::info($product);

                $category = Category::firstOrCreate(
                    [
                        'title' => $product['category']
                    ]
                );
                
                if(array_key_exists('brand', $product)) {
                    $brand = Brand::firstOrCreate(
                        [
                            'title' => $product['brand']
                        ]
                    );
                } else {
                    $brand = null;
                }
                  
                $phone = Phone::create([
                    'title' => $product['title'],
                    'description' => $product['description'],
                    'category_id' => $category->id,
                    'price' => $product['price'],
                    'discountPercentage' => $product['discountPercentage'],
                    'rating' => $product['rating'],
                    'stock' => $product['stock'],
                    'brand_id' => $brand != null ? $brand->id : null,
                    'sku' => $product['sku'],
                    'weight' => $product['weight'],
                    'dimensions' => json_encode($product['dimensions']),
                    'warrantyInformation' => $product['warrantyInformation'],
                    'shippingInformation' => $product['shippingInformation'],
                    'availabilityStatus' => $product['availabilityStatus'],
                    'returnPolicy' => $product['returnPolicy'],
                    'minimumOrderQuantity' => $product['minimumOrderQuantity'],
                    'meta' => json_encode($product['meta']),
                    'thumbnail' => $product['thumbnail'],
                ]);
    
                $tagIDs = [];
                foreach($product['tags'] as $tag) {
                    $tagIDs[] = ProductTag::firstOrCreate(['title' => $tag])->id;
                }
                $phone->tags()->sync($tagIDs);
    
                $reviewsArr = [];
                foreach($product['reviews'] as $review) {
                    $reviewsArr[] = [
                        'rating' => $review['rating'],
                        'comment' => $review['comment'],
                        'reviewerID' => null,
                    ];
                }
                $phone->reviews()->createMany($reviewsArr);
    
                $imagesArr = [];
                foreach($product['images'] as $image) {
                    $imagesArr[] = [
                        'imageURL' => $image,
                        'product_id' => $phone->id
                    ];
                }
                $phone->images()->createMany($imagesArr);
            }
        });

        $this->info('Данные получены и добалвены в бд');
    }
}
