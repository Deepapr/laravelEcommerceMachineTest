<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Log;

class ProductImportService
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Import products from Excel/CSV file
     */
    public function import(array $rows): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
            'imported_ids' => []
        ];

        foreach ($rows as $rowIndex => $row) {
            try {
                $result = $this->importRow($row);
                if ($result) {
                    $results['success']++;
                    $results['imported_ids'][] = $result;
                } else {
                    $results['failed']++;
                    $results['errors'][] = "Row " . ($rowIndex + 2) . ": Missing required fields";
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = "Row " . ($rowIndex + 2) . ": " . $e->getMessage();
                Log::error("Product import error at row " . ($rowIndex + 2), [
                    'error' => $e->getMessage(),
                    'row' => $row
                ]);
            }
        }

        return $results;
    }

    /**
     * Import a single row
     */
    protected function importRow(array $row): ?int
    {
        // Validate required fields
        if (empty($row['product_name']) || empty($row['price']) || empty($row['quantity'])) {
            return null;
        }

        try {
            // Get or create category
            $category = null;
            if (!empty($row['category'])) {
                $category = Category::firstOrCreate(
                    ['name' => $row['category']],
                    ['name' => $row['category']]
                );
            }

            // Get or create color
            $color = null;
            if (!empty($row['color'])) {
                $color = Color::firstOrCreate(
                    ['name' => $row['color']],
                    ['name' => $row['color']]
                );
            }

            // Get or create size
            $size = null;
            if (!empty($row['size'])) {
                $size = Size::firstOrCreate(
                    ['name' => $row['size']],
                    ['name' => $row['size']]
                );
            }

            // Create product
            $product = Product::create([
                'name' => $row['product_name'],
                'description' => $row['description'] ?? null,
                'category_id' => $category?->id,
                'color_id' => $color?->id,
                'size_id' => $size?->id,
                'quantity' => (int)$row['quantity'],
                'price' => (float)$row['price'],
                'sku' => $row['sku'] ?? null,
                'active' => true
            ]);

            // Import image if provided
            if (!empty($row['image_url'])) {
                try {
                    $imagePath = $this->imageService->downloadAndConvert($row['image_url']);
                    if ($imagePath) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'is_primary' => true
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning("Failed to download image for product {$product->id}: " . $e->getMessage());
                }
            }

            return $product->id;

        } catch (\Exception $e) {
            throw new \Exception("Failed to create product: " . $e->getMessage());
        }
    }
}
