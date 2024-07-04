<?php

namespace App\Console\Commands;

use App\Http\Controllers\API\ApiIntegrationController;
use Illuminate\Console\Command;

class ImportMoradImoveis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:moradimoveis';
    protected $description = 'Importar imóveis da Morad Rocha';

    /**
     * The console command description.
     *
     * @var string
     */


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $controller = new ApiIntegrationController();
        $controller->importMoradImoveis();

        $this->info('Importação de imóveis concluída com sucesso.');
    }
}
