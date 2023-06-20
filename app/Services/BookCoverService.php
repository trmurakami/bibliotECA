<?php

namespace App\Services;

class BookCoverService
{
    public function upload($path, $number)
    {
        // Lógica para fazer o upload da capa do livro
        // Exemplo: mover o arquivo para a pasta adequada ou salvar o caminho da capa no banco de dados
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $coverPath = $this->storeCover($path, $number, $extension);
        // Resto da lógica
    }

    private function storeCover($path, $number, $extension)
    {
        // Lógica para armazenar o arquivo de capa
        // Supondo que você tenha uma pasta chamada "covers" dentro da pasta de armazenamento (storage)
        // Crie uma estrutura de pasta baseada no número do registro para evitar conflitos de nomes
        $directory = 'covers/' . $number[0] . '/' . $number[1] . '/' . $number[2] . '/';
        $filename = $number . '.' . $extension;
        $fullPath = $directory . $filename;

        // Mova o arquivo para o diretório adequado
        // Certifique-se de que a pasta de destino exista e tenha permissões de escrita
        \Storage::disk('local')->putFileAs($directory, $path, $filename);

        return $fullPath;
    }
}