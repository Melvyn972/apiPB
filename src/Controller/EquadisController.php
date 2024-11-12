<?php

namespace App\Controller;

use App\Service\EquadisService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EquadisController extends AbstractController
{
    private $equadisService;

    public function __construct(EquadisService $equadisService)
    {
        $this->equadisService = $equadisService;
    }

    #[Route('/', name: 'fetch_products')]
    public function fetchProducts(): Response
    {
        if (!$this->equadisService->signin()) {
            return new Response('Authentication failed', 401);
        }

        $gtins = $this->equadisService->getUpdatedProductsGTINs();

        if (empty($gtins)) {
            return new Response('No updated products found', 404);
        }

        return $this->render('equadis/gtins.html.twig', [
            'gtins' => $gtins,
        ]);
    }

    #[Route('/product/{gtin}', name: 'fetch_product_details')]
    public function fetchProductDetails(string $gtin): Response
    {
        if (!$this->equadisService->signin()) {
            return new Response('Authentication failed', 401);
        }

        $productDetails = $this->equadisService->getProducts([$gtin]);

        return $this->render('equadis/product_details.html.twig', [
            'product' => $productDetails,
        ]);
    }

    #[Route('/products/all', name: 'fetch_all_product_details')]
    public function fetchAllProductDetails(): Response
    {
        if (!$this->equadisService->signin()) {
            return new Response('Authentication failed', 401);
        }

        $gtins = $this->equadisService->getUpdatedProductsGTINs();

        if (empty($gtins)) {
            return new Response('No updated products found', 404);
        }

        $productDetails = $this->equadisService->getProducts($gtins);

        if (empty($productDetails) || !is_array($productDetails)) {
            return new Response('Invalid product details', 500);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = array_keys($productDetails[0]);
        foreach ($headers as $columnIndex => $header) {
            $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex((int)$columnIndex + 1) . '1';
            $sheet->setCellValue($cellCoordinate, $header);
        }

        foreach ($productDetails as $rowIndex => $product) {
            if (!is_array($product)) {
                continue;
            }
            foreach ($headers as $columnIndex => $header) {
                $value = $product[$header] ?? '';
                $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex((int)$columnIndex + 1) . (string)($rowIndex + 2);
                $sheet->setCellValue($cellCoordinate, is_array($value) ? json_encode($value) : (string)$value);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'products') . '.xlsx';
        $writer->save($tempFile);

        return new StreamedResponse(function () use ($tempFile) {
            readfile($tempFile);
            unlink($tempFile);
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="products.xlsx"',
        ]);
    }
}