<?php

namespace App\Console\Commands;

use App\Models\ImovelLog;
use App\Models\LinkJsonProperty;
use App\Models\Property;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AtualizarImoveis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imoveis:atualizar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza a carga de imóveis no banco de dados';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Obtém todos os links JSON da tabela LinkJsonProperties
        $linksJson = LinkJsonProperty::all();

        foreach ($linksJson as $linkJson) {
            // Faz uma solicitação HTTP para obter as informações dos imóveis do link JSON
            $response = Http::get($linkJson->link_json);

            if ($response->ok()) {
                // Converte a resposta JSON em um array
                $imoveis = $response->json();

                $imoveisSalvos = 0;
                $imoveisNaoSalvos = 0;

                foreach ($imoveis as $imovelData) {
                    // Verifica se o tipo de imóvel é 1 (casa) antes de salvar
                    if ($imovelData['tipo_imovel'] == 1) {
                        // Tenta salvar o imóvel
                        // Cria um novo objeto Property e preenche seus atributos
                        $property = new Property();
                        $property->code_property_api = $imovelData['code_property_api'];
                        $property->value_condominio = $imovelData['value_condominio'];
                        $property->value_iptu = $imovelData['value_iptu'];
                        $property->title = $imovelData['title'];
                        $property->property_search_id = mt_rand(10000000, 99999999);
                        $property->slug = $imovelData['slug'];
                        $property->property_type_id = $imovelData['property_type_id'];
                        $property->city_id = $imovelData['city_id'];
                        $property->address = $imovelData['address'];
                        $property->phone = $imovelData['phone'];
                        $property->email = $imovelData['email'];
                        $property->website = $imovelData['website'];
                        $property->property_purpose_id = $imovelData['purpose'];
                        $property->price = $imovelData['price'];
                        $property->period = $imovelData['period'] ?? null;
                        $property->area = $imovelData['area'];
                        $property->number_of_unit = $imovelData['unit'];
                        $property->number_of_room = $imovelData['room'];
                        $property->number_of_bedroom = $imovelData['bedroom'];
                        $property->number_of_bathroom = $imovelData['bathroom'];
                        $property->number_of_floor = $imovelData['floor'];
                        $property->number_of_kitchen = $imovelData['kitchen'];
                        $property->number_of_parking = $imovelData['parking'];
                        $property->video_link = $imovelData['video_link'];
                        $property->google_map_embed_code = $imovelData['google_map_embed_code'];
                        $property->description = $imovelData['description'];
                        $property->status = $imovelData['status'];
                        $property->is_featured = $imovelData['featured'];
                        $property->urgent_property = $imovelData['urgent_property'];
                        $property->top_property = $imovelData['top_property'];
                        $property->seo_title = $imovelData['seo_title'] ?? $imovelData['title'];
                        $property->seo_description = $imovelData['seo_description'] ?? $imovelData['title'];


                        if ($property->save()) {
                            $imoveisSalvos++;
                        } else {
                            $imoveisNaoSalvos++;
                        }
                    } else {
                        $imoveisNaoSalvos++;
                    }
                }

                // Registra as informações na tabela ImovelLog
                ImovelLog::create([
                    'user_id' => $linkJson->user_id,
                    'data' => now()->toDateString(),
                    'imoveis_salvos' => $imoveisSalvos,
                    'imoveis_nao_salvos' => $imoveisNaoSalvos,
                ]);
            } else {
                $this->error('Falha ao obter as informações dos imóveis do link: ' . $linkJson->link_json);
            }
        }

        // Exibe uma mensagem de sucesso quando a carga de imóveis é atualizada com sucesso
        $this->info('Carga de imóveis atualizada com sucesso!');
    }
}
