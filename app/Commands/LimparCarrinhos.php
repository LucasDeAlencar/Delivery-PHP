<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class LimparCarrinhos extends BaseCommand
{
    protected $group       = 'Manutenção';
    protected $name        = 'carrinho:limpar';
    protected $description = 'Remove itens órfãos do carrinho temporário';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('Iniciando limpeza de carrinhos órfãos...', 'yellow');
        
        // 1. Remove itens de produtos que não existem mais
        $produtosInexistentes = $db->query("
            SELECT ct.id, ct.produto_id, ct.produto_nome 
            FROM carrinho_temporario ct 
            LEFT JOIN produtos p ON ct.produto_id = p.id 
            WHERE p.id IS NULL
        ")->getResultArray();
        
        if (!empty($produtosInexistentes)) {
            $ids = array_column($produtosInexistentes, 'id');
            $db->table('carrinho_temporario')->whereIn('id', $ids)->delete();
            CLI::write('Removidos ' . count($produtosInexistentes) . ' itens de produtos inexistentes', 'green');
        }
        
        // 2. Remove itens de produtos inativos
        $produtosInativos = $db->query("
            SELECT ct.id, ct.produto_id, ct.produto_nome 
            FROM carrinho_temporario ct 
            INNER JOIN produtos p ON ct.produto_id = p.id 
            WHERE p.ativo = 0
        ")->getResultArray();
        
        if (!empty($produtosInativos)) {
            $ids = array_column($produtosInativos, 'id');
            $db->table('carrinho_temporario')->whereIn('id', $ids)->delete();
            CLI::write('Removidos ' . count($produtosInativos) . ' itens de produtos inativos', 'green');
        }
        
        // 3. Remove itens muito antigos (mais de 7 dias)
        $itensAntigos = $db->table('carrinho_temporario')
            ->where('criado_em <', date('Y-m-d H:i:s', strtotime('-7 days')))
            ->countAllResults();
            
        if ($itensAntigos > 0) {
            $db->table('carrinho_temporario')
                ->where('criado_em <', date('Y-m-d H:i:s', strtotime('-7 days')))
                ->delete();
            CLI::write('Removidos ' . $itensAntigos . ' itens antigos (>7 dias)', 'green');
        }
        
        CLI::write('Limpeza concluída!', 'green');
    }
}
