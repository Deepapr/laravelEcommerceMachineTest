<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show import page
     */
    public function show()
    {
        return view('admin.import.show');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new ProductImport, $request->file('file'));

        return back()->with('success', 'Products imported successfully');
    }
    /**
     * Handle import
     */
    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:5120'
        ]);

        $file = $request->file('file');
        $rows = $this->parseFile($file);

        if (empty($rows)) {
            return back()->with('error', 'No data found in file');
        }

        $success = 0;
        $failed = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            try {
                $this->importRow($row);
                $success++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = 'Row ' . ($index + 2) . ': ' . $e->getMessage();
            }
        }

        return back()->with([
            'success' => "Import completed. {$success} success, {$failed} failed.",
            'import_errors' => $errors
        ]);
    }

    /**
     * Import one row
     */
    private function importRow(array $row): void
    {
        if (
            empty($row['product_name']) ||
            empty($row['quantity']) ||
            empty($row['price'])
        ) {
            throw new \Exception('Missing required fields');
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
            $imagePath = $this->downloadAndConvertImage($row['image_url']);
        }

        Product::create([
            'name' => trim($row['product_name']),
            'category_id' => $category?->id,
            'color_id' => $color?->id,
            'size_id' => $size?->id,
            'qty' => (int)$row['quantity'],
            'price' => (float)$row['price'],
            'image' => $imagePath,
        ]);
    }

    /**
     * Download image and convert to WEBP
     */
    private function downloadAndConvertImage(string $url): ?string
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
            Log::warning('Image import failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Parse CSV or Excel
     */
    private function parseFile($file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $path = $file->store('imports');
        $fullPath = storage_path('app/' . $path);

        if (in_array($extension, ['xlsx', 'xls'])) {
            return $this->parseExcel($fullPath);
        }

        return $this->parseCSV($fullPath);
    }

    /**
     * Parse Excel
     */
    private function parseExcel(string $filePath): array
    {
        $rows = [];
        $sheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath)->getActiveSheet();

        $header = [];
        foreach ($sheet->toArray() as $index => $row) {
            if ($index === 0) {
                $header = array_map('strtolower', $row);
                continue;
            }

            $rows[] = array_combine($header, $row);
        }

        return $rows;
    }

    /**
     * Parse CSV
     */
    private function parseCSV(string $filePath): array
    {
        $rows = [];
        $header = [];

        $handle = fopen($filePath, 'r');
        while (($data = fgetcsv($handle)) !== false) {
            if (!$header) {
                $header = array_map('strtolower', $data);
                continue;
            }

            $rows[] = array_combine($header, $data);
        }

        fclose($handle);
        return $rows;
    }
}
