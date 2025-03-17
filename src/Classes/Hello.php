<?php

namespace App\Process\Classes;

/**
 * Genera mesnaje JSON.
 */
Class Hello {
    /**
     * @param string $nombre Nombre de la persona a saludar.
     * @return string JSON con el mensaje de saludo.
     */
    public function saludar(string $nombre): string {
        $message = [
            "message" => $nombre . ", el test funciona correctamente"
        ];
        return json_encode($message, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
