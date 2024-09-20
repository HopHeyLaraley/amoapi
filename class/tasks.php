<?php

require_once "entity.php";

class Task extends Entity{

    public function createTask($lead_id, $contact_id) {
        $task_time = ($lead_id == -1) ? 15 : 5;
        $task_text = ($lead_id == -1) ? "Повторная заявка" : "Обработать заявку";
        $entity_id = ($lead_id == -1) ? (int)$contact_id : (int)$lead_id;
        $entity_type = ($lead_id == -1) ? "contacts" : "leads";

        $data = [
            [
                'task_type_id' => 1,
                'entity_id' => $entity_id,
                'entity_type' => $entity_type,
                'responsible_user_id' => 10516613,
                'complete_till' => time() + (60 * $task_time),
                'text' => $task_text
            ]
        ];

        $this->amo->post('/api/v4/tasks', $data);
    }
}

?>