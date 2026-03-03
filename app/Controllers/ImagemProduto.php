<?php

namespace App\Controllers;

class ImagemProduto extends BaseController
{
    public function servir($nomeArquivo = null)
    {
        if (!$nomeArquivo) {
            return $this->response->setStatusCode(404);
        }

        $caminhoImagem = FCPATH . 'uploads/produtos/' . $nomeArquivo;

        // Se a imagem existe, servir
        if (file_exists($caminhoImagem)) {
            $mimeType = mime_content_type($caminhoImagem);
            
            return $this->response
                ->setHeader('Content-Type', $mimeType)
                ->setHeader('Cache-Control', 'public, max-age=31536000')
                ->setBody(file_get_contents($caminhoImagem));
        }

        // Se não existe, redirecionar para imagem padrão do Unsplash
        $imagensPadrao = [
            'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1594212699903-ec8a3eca50f5?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop'
        ];

        return redirect()->to($imagensPadrao[array_rand($imagensPadrao)]);
    }
}
