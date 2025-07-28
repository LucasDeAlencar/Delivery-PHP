<?php

/**
 * Helper para gerenciamento de timezone
 * Garante que todas as datas sejam tratadas no timezone de São Paulo
 */

if (!function_exists('sao_paulo_now')) {
    /**
     * Retorna a data/hora atual no timezone de São Paulo
     * 
     * @param string $format Formato da data (padrão: Y-m-d H:i:s)
     * @return string
     */
    function sao_paulo_now($format = 'Y-m-d H:i:s') {
        $datetime = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        return $datetime->format($format);
    }
}

if (!function_exists('format_sao_paulo_date')) {
    /**
     * Formata uma data para o timezone de São Paulo
     * 
     * @param string $date Data a ser formatada
     * @param string $format Formato de saída (padrão: d/m/Y H:i:s)
     * @return string
     */
    function format_sao_paulo_date($date, $format = 'd/m/Y H:i:s') {
        if (empty($date)) {
            return '';
        }
        
        try {
            $datetime = new DateTime($date);
            $datetime->setTimezone(new DateTimeZone('America/Sao_Paulo'));
            return $datetime->format($format);
        } catch (Exception $e) {
            return $date; // Retorna a data original se houver erro
        }
    }
}

if (!function_exists('sao_paulo_datetime')) {
    /**
     * Cria um objeto DateTime no timezone de São Paulo
     * 
     * @param string $time Tempo a ser convertido (padrão: 'now')
     * @return DateTime
     */
    function sao_paulo_datetime($time = 'now') {
        return new DateTime($time, new DateTimeZone('America/Sao_Paulo'));
    }
}

if (!function_exists('convert_to_sao_paulo')) {
    /**
     * Converte uma data de qualquer timezone para São Paulo
     * 
     * @param string $date Data a ser convertida
     * @param string $fromTimezone Timezone de origem (padrão: UTC)
     * @param string $format Formato de saída (padrão: Y-m-d H:i:s)
     * @return string
     */
    function convert_to_sao_paulo($date, $fromTimezone = 'UTC', $format = 'Y-m-d H:i:s') {
        if (empty($date)) {
            return '';
        }
        
        try {
            $datetime = new DateTime($date, new DateTimeZone($fromTimezone));
            $datetime->setTimezone(new DateTimeZone('America/Sao_Paulo'));
            return $datetime->format($format);
        } catch (Exception $e) {
            return $date; // Retorna a data original se houver erro
        }
    }
}

if (!function_exists('humanize_sao_paulo_date')) {
    /**
     * Retorna uma data humanizada no timezone de São Paulo
     * 
     * @param string $date Data a ser humanizada
     * @return string
     */
    function humanize_sao_paulo_date($date) {
        if (empty($date)) {
            return 'Não informado';
        }
        
        try {
            $datetime = new DateTime($date);
            $datetime->setTimezone(new DateTimeZone('America/Sao_Paulo'));
            $now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
            
            $diff = $now->diff($datetime);
            
            if ($diff->days == 0) {
                if ($diff->h == 0) {
                    if ($diff->i == 0) {
                        return 'Agora mesmo';
                    }
                    return $diff->i . ' minuto' . ($diff->i > 1 ? 's' : '') . ' atrás';
                }
                return $diff->h . ' hora' . ($diff->h > 1 ? 's' : '') . ' atrás';
            } elseif ($diff->days == 1) {
                return 'Ontem às ' . $datetime->format('H:i');
            } elseif ($diff->days < 7) {
                return $diff->days . ' dia' . ($diff->days > 1 ? 's' : '') . ' atrás';
            } else {
                return $datetime->format('d/m/Y H:i');
            }
        } catch (Exception $e) {
            return $date;
        }
    }
}