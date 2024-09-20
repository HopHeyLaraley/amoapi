<?php

require_once "entity.php";

class Lead extends Entity{

    public function createLead($contact_id, $service) {
        $price = 0;
        if ($service == "diag") {
            $service = 'Диагностика';
            $price = 100;
        } elseif ($service == 'fix') {
            $service = 'Ремонт';
            $price = 500;
        }

        $data = [
            [
                'responsible_user_id' => 10516613,
                'price' => $price,
                'custom_fields_values' => [
                    [
                        'field_id' => 169249,  // Поле услуги
                        'values' => [
                            ['value' => $service]
                        ]
                    ]
                ],
                '_embedded' => [
                    'contacts' => [
                        ['id' => $contact_id]
                    ]
                ]
            ]
        ];

        $response = $this->amo->post('/api/v4/leads', $data);
        return $response['_embedded']['leads'][0]['id'] ?? null;
    }
}

?>