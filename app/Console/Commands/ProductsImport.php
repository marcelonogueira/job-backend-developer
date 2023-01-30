<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ProductsImport extends Command
{
    protected $urlImportacao = 'https://fakestoreapi.com/products/';

    protected $urlApiLocal = 'http://localhost:8000/api/product';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importação de produtos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->option('id');

        try {
            $response = Http::get($this->urlImportacao . $id);

            if ($response->failed()) {
                $this->error('Nenhum produto encontrado');
                return Command::FAILURE;
            }

            $products = $response->json();

            $this->addProducts($products, $id);

            return Command::SUCCESS;
        } catch (\Exception $ex) {
            $this->error("Ocorreu um erro no processamento: {$ex->getMessage()}\n");
            return false;
        }

        return true;
    }

    private function addProducts(array $products, int $id = null)
    {
        if ($id) {
            $this->addProduct($products);
        } else {
            foreach ($products as $product) {
                $this->addProduct($product);
            }
        }
    }

    private function addProduct(array $product)
    {
        $response = Http::post($this->urlApiLocal, [
            'name' => $product['title'],
            'price' => $product['price'],
            'description' => $product['description'],
            'category' => $product['category'],
            'image_url' => $product['image'],
        ]);

        if ($response->failed()) {
            $this->error('Erro ao inserir o produto: ' . $product['title'] . json_encode($response->json()['data']));
        } else {
            $this->info('Produto: ' . $product['title'] . ' inserido com sucesso!!!');
        }
    }
}
