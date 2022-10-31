<?php

namespace App\Models;

class Organization{
    
    /**
     * ID da organização
     * @var integer
     */
    public $id = 1;

    /**
     * Nome da organização
     * @var string
     */
    public $name = '- Manual do Calouro';

    /**
     * Site da organização
     * @var string
     */
    public $site = 'https://youtube.com/wdevofical';

    /**
     * Descrição da organização
     * @var string
     */
    public $description = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
}