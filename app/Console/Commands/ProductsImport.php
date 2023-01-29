<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ProductsImport extends Command
{
    /** Deixaria as urls no .env */
    protected $urlImportacao = 'https://fakestoreapi.com/products/';

    protected $urlApiLocal = 'http://localhost:8000/api/produto';

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

            $produtos = $response->json();

            $this->incluirProdutos($produtos, $id);

            return Command::SUCCESS;
        } catch (\Exception $ex) {
            $this->error("Ocorreu um erro no processamento: {$ex->getMessage()}\n");
            return false;
        }

        return true;
    }

    private function incluirProdutos($produtos, $id)
    {
        if ($id) {
            $this->incluirProduto($produtos);
        } else {
            foreach ($produtos as $produto) {
                $this->incluirProduto($produto);
            }
        }
    }

    private function incluirProduto($produto)
    {
        $response = Http::post($this->urlApiLocal, [
            'name' => $produto['title'],
            'price' => $produto['price'],
            'description' => $produto['description'],
            'category' => $produto['category'],
            'image_url' => $produto['image'],
        ]);

        if ($response->failed()) {
            $this->error('Erro ao inserir o produto: ' . $produto['title']);
        } else {
            $this->info('Produto: ' . $produto['title'] . ' inserido com sucesso!!!');
        }
    }
}
