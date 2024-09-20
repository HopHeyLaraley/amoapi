<?php

require_once "entity.php";

class Contact extends Entity{

    private function normalizePhone($phone){
        $phone = (int)$phone;
        return substr($phone,1);
    }

    public function findDouble($phone, $mail) {
        
        $phone = $this->normalizePhone($phone);
    
        $phoneQuery = urlencode($phone);
        $response = $this->amo->get("/api/v4/contacts?query={$phoneQuery}");
    
        if (!empty($response['_embedded']['contacts'])) {
            return $response['_embedded']['contacts'][0]['id'];
        }
    
        $mailQuery = urlencode($mail);
        $response = $this->amo->get("/api/v4/contacts?query={$mailQuery}");
    
        if (!empty($response['_embedded']['contacts'])) {
            return $response['_embedded']['contacts'][0]['id'];
        }

        return false;
    }

    public function createContact($name, $phone, $mail, $city) {
        $data = [
            [
                'name' => $name,
                'responsible_user_id' => 10516613,
                'custom_fields_values' => [
                    [
                        'field_id' => 169203,  // Поле телефона
                        'values' => [
                            ['value' => $phone]
                        ]
                    ],
                    [
                        'field_id' => 169205,  // Поле email
                        'values' => [
                            ['value' => $mail]
                        ]
                    ],
                    [
                        'field_id' => 169251,  // Поле города
                        'values' => [
                            ['value' => $city]
                        ]
                    ]
                ]
            ]
        ];

        $response = $this->amo->post('/api/v4/contacts', $data);
        return $response['_embedded']['contacts'][0]['id'] ?? null;
    }
}

?>