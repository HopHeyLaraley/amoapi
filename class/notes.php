<?php

require_once "entity.php";

class Note extends Entity{

    public function createNote($lead_id, $text) {
        $data = [
            [
                'note_type' => 'common',
                'params' => [
                    'text' => $text
                ]
            ]
        ];
    
        $this->amo->post("/api/v4/leads/{$lead_id}/notes", $data);
    }
}

?>