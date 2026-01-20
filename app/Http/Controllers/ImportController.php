<?php

namespace App\Http\Controllers;

use App\Services\ProductImportService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    protected ProductImportService $importService;

    public function __construct(ProductImportService $importService)
    {
        $this->importService = $importService;
        $this->middleware('auth');
    }

    /**
     * Show import form
     */
    public function show()
    {
        return view('admin.import.show');
    }

    /**
     * Process import
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120'
        ]);

        try {
            $file = $validated['file'];
            $rows = $this->parseFile($file);

            if (empty($rows)) {
                return back()->with('error', 'No data found in the file');
            }

            $results = $this->importService->import($rows);

            $successMessage = "Import completed! {$results['success']} products imported successfully.";
            if ($results['failed'] > 0) {
                $successMessage .= " {$results['failed']} rows failed.";
            }

            return back()
                ->with('success', $successMessage)
                ->with('import_errors', $results['errors']);

        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Parse CSV or Excel file
     */
    protected function parseFile($file): array
    {
        $extension = $file->getClientOriginalExtension();
        $filePath = $file->store('imports');

        try {
            if (in_array(strtolower($extension), ['xlsx', 'xls'])) {
                return $this->parseExcel(storage_path('app/' . $filePath));
            } else {
                return $this->parseCSV(storage_path('app/' . $filePath));
            }
        } finally {
            @unlink(storage_path('app/' . $filePath));
        }
    }

    /**
     * Parse Excel file using PHP Spreadsheet
     */
    protected function parseExcel(string $filePath): array
    {
        $rows = [];

        try {
            // Check if PhpSpreadsheet is available
            if (!class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
                // Fallback to simple CSV conversion
                return $this->parseCSV($filePath);
            }
            
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();

            $headerRow = null;
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                // Skip empty rows
                if (array_filter($rowData)) {
                    if (!$headerRow) {
                        $headerRow = $rowData;
                    } else {
                        $rows[] = array_combine($headerRow, $rowData);
                    }
                }
            }
        } catch (\Exception $e) {
            // Fallback to CSV parsing
            return $this->parseCSV($filePath);
        }

        return $rows;
    }

    /**
     * Parse CSV file
     */
    protected function parseCSV(string $filePath): array
    {
        $rows = [];
        $headerRow = null;

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                // Skip empty rows
                if (!array_filter($data)) {
                    continue;
                }

                if (!$headerRow) {
                    $headerRow = array_map('trim', $data);
                } else {
                    $rowData = array_map('trim', $data);
                    $rows[] = array_combine($headerRow, $rowData);
                }
            }
            fclose($handle);
        }

        return $rows;
    }
}
