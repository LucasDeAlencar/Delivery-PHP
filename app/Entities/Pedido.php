<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Pedido extends Entity {

    protected $dates = [
        'criado_em',
        'atualizado_em',
        'deletado_em'
    ];

    protected $casts = [
        'id' => 'integer',
        'usuario_id' => 'integer',
        'bairro_id' => 'integer',
        'valor_produtos' => 'float',
        'valor_entrega' => 'float',
        'valor_total' => 'float',
        'troco_para' => 'float',
    ];

    // Atributos com valores padrão
    protected $attributes = [
        'status' => 'pendente',
    ];

    /**
     * Getter para status - garante que nunca retorna null/vazio
     */
    public function getStatus(): string {
        $status = $this->attributes['status'] ?? 'pendente';
        return empty($status) ? 'pendente' : strtolower(trim($status));
    }

    /**
     * Retorna o status formatado para exibição
     */
    public function getStatusFormatado(): string {
        $statusMap = [
            'em_aberto'    => 'Em Aberto',
            'pendente'     => 'Pendente',
            'confirmado'   => 'Confirmado',
            'preparando'   => 'Em Preparação',
            'saiu_entrega' => 'Saiu para Entrega',
            'finalizado'   => 'Finalizado',
            'cancelado'    => 'Cancelado',
            'inativo'      => 'Inativo'
        ];

        return $statusMap[$this->status] ?? $this->status;
    }

    /**
     * Retorna a classe CSS para o badge de status
     */
    public function getStatusBadgeClass(): string {
        $classMap = [
            'em_aberto'    => 'badge-orange',
            'pendente'     => 'badge-warning',
            'confirmado'   => 'badge-info',
            'preparando'   => 'badge-primary',
            'saiu_entrega' => 'badge-secondary',
            'finalizado'   => 'badge-success',
            'cancelado'    => 'badge-danger',
            'inativo'      => 'badge-secondary'
        ];

        return $classMap[$this->status] ?? 'badge-secondary';
    }

    /**
     * Retorna o ícone para o status
     */
    public function getStatusIcon(): string {
        $iconMap = [
            'em_aberto'    => 'fas fa-folder-open',
            'pendente'     => 'fas fa-clock',
            'confirmado'   => 'fas fa-check-circle',
            'preparando'   => 'fas fa-utensils',
            'saiu_entrega' => 'fas fa-motorcycle',
            'finalizado'   => 'fas fa-check-double',
            'cancelado'    => 'fas fa-times-circle',
            'inativo'      => 'fas fa-ban'
        ];

        return $iconMap[$this->status] ?? 'fas fa-question-circle';
    }

    /**
     * Verifica se o pedido pode ser editado
     */
    public function podeEditar(): bool {
        return in_array($this->status, ['em_aberto', 'pendente', 'confirmado']);
    }

    /**
     * Verifica se o pedido pode ser cancelado
     */
    public function podeCancelar(): bool {
        return !in_array($this->status, ['finalizado', 'cancelado']);
    }
}
