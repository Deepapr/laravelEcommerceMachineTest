<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if (
                empty($row['product_name']) ||
                empty($row['price']) ||
                empty($row['quantity'])
            ) {
                continue;
            }

            $category = !empty($row['category'])
                ? Category::firstOrCreate(['name' => trim($row['category'])])
                : null;

            $color = !empty($row['color'])
                ? Color::where('name', trim($row['color']))->first()
                : null;

            $size = !empty($row['size'])
                ? Size::where('name', trim($row['size']))->first()
                : null;

            $imagePath = null;
            if (!empty($row['image_url'])) {
                $imagePath = $this->downloadAndConvertToWebp(trim($row['image_url']));
            }

            Product::create([
                'name'        => trim($row['product_name']),
                'category_id'=> $category?->id,
                'color_id'   => $color?->id,
                'size_id'    => $size?->id,
                'qty'        => (int) $row['quantity'],
                'price'      => (float) $row['price'],
                'description'=> $row['description'] ?? null,
                'sku'        => $row['sku'] ?? null,
                'image'      => $imagePath,
            ]);
        }
    }

    private function downloadAndConvertToWebp(string $url): ?string
    {
        try {
            $response = Http::timeout(20)->get($url);

            if (!$response->successful()) {
                return null;
            }

            $image = imagecreatefromstring($response->body());
            if (!$image) {
                return null;
            }

            $fileName = Str::uuid() . '.webp';
            $path = storage_path('app/public/products');

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            imagewebp($image, $path . '/' . $fileName, 80);
            imagedestroy($image);

            return 'products/' . $fileName;

        } catch (\Exception $e) {
            Log::warning($e->getMessage());
            return null;
        }
    }
}
