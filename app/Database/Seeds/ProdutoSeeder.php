<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run()
    {
        echo "Iniciando ProdutoSeeder...\n";
        
        // Verificar se existem categorias
        $categoriaModel = new \App\Models\CategoriaModel();
        $categorias = $categoriaModel->where('ativo', true)->findAll();
        
        if (empty($categorias)) {
            echo "Criando categorias...\n";
            
            // Criar algumas categorias primeiro
            $categoriaData = [
                [
                    'nome' => 'Pizzas',
                    'slug' => 'pizzas',
                    'ativo' => true,
                    'criado_em' => date('Y-m-d H:i:s'),
                    'atualizado_em' => date('Y-m-d H:i:s')
                ],
                [
                    'nome' => 'Bebidas',
                    'slug' => 'bebidas',
                    'ativo' => true,
                    'criado_em' => date('Y-m-d H:i:s'),
                    'atualizado_em' => date('Y-m-d H:i:s')
                ],
                [
                    'nome' => 'Massas',
                    'slug' => 'massas',
                    'ativo' => true,
                    'criado_em' => date('Y-m-d H:i:s'),
                    'atualizado_em' => date('Y-m-d H:i:s')
                ],
                [
                    'nome' => 'Sobremesas',
                    'slug' => 'sobremesas',
                    'ativo' => true,
                    'criado_em' => date('Y-m-d H:i:s'),
                    'atualizado_em' => date('Y-m-d H:i:s')
                ],
                [
                    'nome' => 'Entradas',
                    'slug' => 'entradas',
                    'ativo' => true,
                    'criado_em' => date('Y-m-d H:i:s'),
                    'atualizado_em' => date('Y-m-d H:i:s')
                ]
            ];
            
            $this->db->table('categorias')->insertBatch($categoriaData);
            echo "Categorias criadas com sucesso!\n";
            
            // Recarregar categorias
            $categorias = $categoriaModel->where('ativo', true)->findAll();
        } else {
            echo "Categorias já existem: " . count($categorias) . " encontradas\n";
        }
        
        // Produtos de exemplo
        $produtos = [
            [
                'categoria_id' => $categorias[0]->id, // Pizzas
                'nome' => 'Pizza Margherita',
                'slug' => 'pizza-margherita',
                'ingredientes' => 'Molho de tomate, mussarela, manjericão fresco, azeite extra virgem',
                'preco' => 35.90,
                'ativo' => true,
                'imagem' => '',
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ],
            [
                'categoria_id' => $categorias[0]->id, // Pizzas
                'nome' => 'Pizza Pepperoni',
                'slug' => 'pizza-pepperoni',
                'ingredientes' => 'Molho de tomate, mussarela, pepperoni, orégano',
                'preco' => 42.90,
                'ativo' => true,
                'imagem' => '',
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ],
            [
                'categoria_id' => $categorias[0]->id, // Pizzas
                'nome' => 'Pizza Quattro Stagioni',
                'slug' => 'pizza-quattro-stagioni',
                'ingredientes' => 'Molho de tomate, mussarela, presunto, cogumelos, alcachofras, azeitonas',
                'preco' => 48.90,
                'ativo' => true,
                'imagem' => '',
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ],
            [
                'categoria_id' => $categorias[1]->id, // Bebidas
                'nome' => 'Coca-Cola 350ml',
                'slug' => 'coca-cola-350ml',
                'ingredientes' => 'Refrigerante de cola gelado',
                'preco' => 5.90,
                'ativo' => true,
                'imagem' => '',
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ],
            [
                'categoria_id' => $categorias[1]->id, // Bebidas
                'nome' => 'Suco de Laranja Natural',
                'slug' => 'suco-laranja-natural',
                'ingredientes' => 'Suco de laranja natural, sem conservantes',
                'preco' => 8.90,
                'ativo' => true,
                'imagem' => '',
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ],
            [
                'categoria_id' => $categorias[2]->id, // Massas
                'nome' => 'Espaguete à Carbonara',
                'slug' => 'espaguete-carbonara',
                'ingredientes' => 'Espaguete, bacon, ovos, queijo parmesão, pimenta do reino',
                'preco' => 28.90,
                'ativo' => true,
                'imagem' => '',
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ],
            [
                'categoria_id' => $categorias[2]->id, // Massas
                'nome' => 'Lasanha Bolonhesa',
                'slug' => 'lasanha-bolonhesa',
                'ingredientes' => 'Massa de lasanha, molho bolonhesa, molho branco, queijo mussarela',
                'preco' => 32.90,
                'ativo' => true,
                'imagem' => '',
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ],
            [
                'categoria_id' => $categorias[3]->id, // Sobremesas
                'nome' => 'Tiramisu',
                'slug' => 'tiramisu',
                'ingredientes' => 'Biscoito champagne, café, mascarpone, ovos, açúcar, cacau em pó',
                'preco' => 18.90,
                'ativo' => true,
                'imagem' => '',
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ],
            [
                'categoria_id' => $categorias[3]->id, // Sobremesas
                'nome' => 'Gelato de Baunilha',
                'slug' => 'gelato-baunilha',
                'ingredientes' => 'Gelato artesanal de baunilha com calda de frutas vermelhas',
                'preco' => 15.90,
                'ativo' => true,
                'imagem' => '',
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ],
            [
                'categoria_id' => $categorias[4]->id, // Entradas
                'nome' => 'Bruschetta Italiana',
                'slug' => 'bruschetta-italiana',
                'ingredientes' => 'Pão italiano, tomate, manjericão, alho, azeite extra virgem',
                'preco' => 12.90,
                'ativo' => true,
                'imagem' => '',
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ],
            [
                'categoria_id' => $categorias[4]->id, // Entradas
                'nome' => 'Antipasto Misto',
                'slug' => 'antipasto-misto',
                'ingredientes' => 'Seleção de queijos, presuntos, azeitonas, tomates secos e pães',
                'preco' => 24.90,
                'ativo' => true,
                'imagem' => '',
                'criado_em' => date('Y-m-d H:i:s'),
                'atualizado_em' => date('Y-m-d H:i:s')
            ]
        ];
        
        // Verificar se já existem produtos para não duplicar
        $produtoModel = new \App\Models\ProdutoModel();
        $produtosExistentes = $produtoModel->findAll();
        
        if (empty($produtosExistentes)) {
            echo "Inserindo " . count($produtos) . " produtos...\n";
            $this->db->table('produtos')->insertBatch($produtos);
            echo "✓ Produtos inseridos com sucesso!\n";
            
            // Mostrar resumo por categoria
            echo "\nResumo dos produtos criados:\n";
            foreach ($categorias as $categoria) {
                $count = 0;
                foreach ($produtos as $produto) {
                    if ($produto['categoria_id'] == $categoria->id) {
                        $count++;
                    }
                }
                if ($count > 0) {
                    echo "- " . $categoria->nome . ": " . $count . " produtos\n";
                }
            }
        } else {
            echo "Produtos já existem na base de dados (" . count($produtosExistentes) . " encontrados).\n";
        }
        
        echo "\nProdutoSeeder concluído!\n";}
    }