<?php

namespace App\Controller;

use App\Service\EquadisService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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
            $rowIndex = (int)$rowIndex + 2;
            error_log(print_r($product, true)); // Ajoutez ceci pour voir chaque produit écrit
            foreach ($headers as $columnIndex => $header) {
                $value = $product[$header] ?? '';
                $columnIndex = (int)$columnIndex + 1;
                $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex) . $rowIndex;
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

    #[Route('/products/table', name: 'fetch_products_table')]
    public function fetchProductsTable(): Response
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

        // Récupérer les clés pour les colonnes
        $headers = array_keys($productDetails[0]);

        return $this->render('equadis/products_table.html.twig', [
            'headers' => $headers,
            'products' => $productDetails,
        ]);
    }

    #[Route('/products/export', name: 'export_products')]
    public function exportProducts(): Response
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
        $sheet->fromArray($headers, NULL, 'A1');

        $row = 2;
        foreach ($productDetails as $product) {
            $rowData = [];
            foreach ($product as $key => $value) {
                $rowData[] = is_array($value) || is_object($value) ? json_encode($value) : $value;
            }
            $sheet->fromArray($rowData, NULL, 'A' . $row);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'products') . '.xlsx';
        $writer->save($tempFile);

        return $this->file($tempFile, 'products.xlsx', ResponseHeaderBag::DISPOSITION_INLINE);
    }

   private const SELECTED_KEYS = [
    'GTIN de l\'article déclaré', 'SKU', 'Parent ou Enfant', 'CODE PARKOD', 'CODE LIGNE', 'Axe du produit',
    'TAG Création', 'Opération Commerciale', 'Prix de base', 'Coefficient prix', 'Prix Vente Public TTC',
    'Point Rouge', 'Statut', 'Video Youtube', 'Taux de TVA', 'Marque', 'Sous-marque', 'Référence interne',
    'GTIN', 'Libellé facture', 'Libellé court', 'Mentions obligatoires complémentaires', 'Brique GPC',
    'Sous-marque', 'Concentration Parfum', 'quantité max de produits au panier', 'Catégorie Niveau 1',
    'Catégorie Niveau 2', 'Catégorie Niveau 3', 'Catégorie Niveau 4', 'Cible consommateur',
    'Date de début de vente au consommateur', 'Date de début de validité de la fiche produit',
    'Type de produit', 'Exclusivité Magasin', 'Type de format spécial ou promotionnel',
    'Produit élu par les Clients', 'Produit élu par les Expertes', 'Produit élu par les Influenceurs',
    'Beauté Engagée', 'Coffret', 'Nom détaillé de la déclinaison', 'Attribut de déclinaison',
    'Meta Title SEO', 'Meta Description SEO', 'Message marketing', 'Famille olfactive', 'Note de tête',
    'Image Note de tête', 'Note de coeur', 'Image Note de coeur', 'Note de fond', 'Image Note de fond',
    'Type de Peau', 'Indice de protection solaire', 'Format', 'Texture du Produit', 'Formulation',
    'Propriété du Produit', 'Couleur', 'Code couleur teinte Hexa', 'Effet attendu', 'Couvrance attendue',
    'Fonctionnalité liée au produit', 'Action pour les soins corps', 'Action pour les soins homme',
    'Effet pour les soins cheveux', 'Type de cheveux', 'Conditions d\'utilisation du produit',
    'Liste des ingrédients', 'Bénéfice produit', 'GTIN cross sell', 'Poids brut', 'Largeur', 'Profondeur',
    'Hauteur', 'Contenance nette', 'Contenu net (code unité de mesure)', 'Avis Experte - Nom de l\'experte',
    'Avis Experte - Avis', 'Code EAN du produit full size', 'Pays d\'origine', 'Minimum de commande',
    'Code devise', 'Code de nomenclature douanière',
];

    #[Route('/products/table2', name: 'fetch_products_table2')]
    public function fetchProductsTable2(): Response
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

        // Filtrer les colonnes selon la liste définie
        $filteredProducts = array_map(function($product) {
            return array_intersect_key($product, array_flip(self::SELECTED_KEYS));
        }, $productDetails);

        // Récupérer les clés pour les colonnes
        $headers = array_keys($filteredProducts[0]);

        return $this->render('equadis/products_table2.html.twig', [
            'headers' => $headers,
            'products' => $filteredProducts,
        ]);
    }

    #[Route('/products/export2', name: 'export_products2')]
    public function exportProducts2(): Response
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

        // Filtrer les colonnes selon la liste définie
        $filteredProducts = array_map(function($product) {
            return array_intersect_key($product, array_flip(self::SELECTED_KEYS));
        }, $productDetails);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ajouter les en-têtes
        $headers = array_keys($filteredProducts[0]);
        $sheet->fromArray($headers, NULL, 'A1');

        // Ajouter les données des produits
        $row = 2;
        foreach ($filteredProducts as $product) {
            $rowData = [];
            foreach ($headers as $key) {
                $value = $product[$key] ?? '';
                $rowData[] = is_array($value) || is_object($value) ? json_encode($value) : $value;
            }
            $sheet->fromArray($rowData, NULL, 'A' . $row);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'products') . '.xlsx';
        $writer->save($tempFile);

        return $this->file($tempFile, 'products.xlsx', ResponseHeaderBag::DISPOSITION_INLINE);
    }
}