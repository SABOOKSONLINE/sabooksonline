<?php
/**
 * Shared Shipping Calculation Helper
 * Used by both web and mobile to calculate accurate shipping fees via Courier Guy API
 */

if (!function_exists('formatCollectionForCourier')) {
    function formatCollectionForCourier(array $addr = null)
    {
        if (empty($addr)) return null;
        $street = trim(($addr['street_number'] ?? '') . ' ' . ($addr['street_name'] ?? ''));
        return [
            "type" => "business",
            "company" => $addr['nickname'] ?? '',
            "street_address" => $street ?: ($addr['street_address'] ?? ''),
            "local_area" => $addr['suburb'] ?? ($addr['local_area'] ?? ''),
            "city" => $addr['city'] ?? '',
            "code" => $addr['postal_code'] ?? ($addr['code'] ?? ''),
            "zone" => $addr['province'] ?? ($addr['zone'] ?? ''),
            "country" => $addr['country_code'] ?? ($addr['country'] ?? 'ZA')
        ];
    }
}

if (!function_exists('calculateCourierGuyShipping')) {
    function calculateCourierGuyShipping($deliveryAddress, $cartItems, $cartModel, $defaultCollectionAddress = null)
    {
        if (!$deliveryAddress || empty($cartItems)) return 0;

        $apiUrl = "https://api.portal.thecourierguy.co.za/v2/rates";
        $apiKey = getenv('COURIER_GUY_API_KEY') ?: '';

        // Group items by effective collection address (publisher default if available, otherwise user default)
        $groups = [];
        foreach ($cartItems as $item) {
            $qty = max(1, (int)($item['cart_item_count'] ?? 1));

            // try publisher default collection address if publisher_id exists
            $collectionAddr = null;
            if (!empty($item['publisher_id'])) {
                $pubDefault = $cartModel->getDefaultCollectionAddress((int)$item['publisher_id']);
                if (!empty($pubDefault)) $collectionAddr = $pubDefault;
            }

            // fallback if item itself includes a collection address array
            if (empty($collectionAddr) && !empty($item['collection_address']) && is_array($item['collection_address'])) {
                $collectionAddr = $item['collection_address'];
            }

            // final fallback to user's default collection address
            if (empty($collectionAddr)) {
                $collectionAddr = $defaultCollectionAddress ?: null;
            }

            $collectionKey = md5(json_encode($collectionAddr));
            if (!isset($groups[$collectionKey])) {
                $groups[$collectionKey] = [
                    'address' => $collectionAddr,
                    'parcels' => []
                ];
            }

            for ($i = 0; $i < $qty; $i++) {
                $groups[$collectionKey]['parcels'][] = [
                    "submitted_length_cm" => isset($item['hc_height_cm']) ? (float)$item['hc_height_cm'] : (isset($item['hc_length_cm']) ? (float)$item['hc_length_cm'] : 10.0),
                    "submitted_width_cm" => isset($item['hc_width_cm']) ? (float)$item['hc_width_cm'] : 10.0,
                    "submitted_height_cm" => isset($item['hc_height_cm']) ? (float)$item['hc_height_cm'] : (isset($item['hc_pages']) ? (float)$item['hc_pages'] / 2 : 5.0),
                    "submitted_weight_kg" => isset($item['hc_weight_kg']) ? (float)$item['hc_weight_kg'] : 1.0
                ];
            }
        }

        $totalRate = 0.0;

        foreach ($groups as $group) {
            $collection = formatCollectionForCourier($group['address']) ?: [
                "type" => "business",
                "company" => "https://www.sabooksonline.co.za/",
                "street_address" => "68 Melville Rd",
                "local_area" => "Illovo",
                "city" => "Sandton",
                "code" => "2196",
                "zone" => "Gauteng",
                "country" => "ZA"
            ];

            $payload = [
                "collection_address" => $collection,
                "collection_contact" => [
                    "name" => $group['address']['contact_name'] ?? "Pearl Khumalo",
                    "mobile_number" => $group['address']['contact_phone'] ?? "+27678523593",
                    "email" => $group['address']['contact_email'] ?? "pearl@sabooksonline.co.za"
                ],
                "delivery_address" => [
                    "type" => $deliveryAddress['delivery_type'] ?? "residential",
                    "company" => $deliveryAddress['company'] ?? "",
                    "street_address" => $deliveryAddress['street_address'] ?? "",
                    "local_area" => $deliveryAddress['local_area'] ?? "",
                    "city" => $deliveryAddress['city'] ?? "",
                    "zone" => $deliveryAddress['zone'] ?? "",
                    "country" => $deliveryAddress['country'] ?? "ZA",
                    "code" => $deliveryAddress['postal_code'] ?? ($deliveryAddress['code'] ?? "")
                ],
                "delivery_contact" => [
                    "name" => $deliveryAddress['full_name'] ?? ($deliveryAddress['name'] ?? ""),
                    "mobile_number" => $deliveryAddress['phone'] ?? ($deliveryAddress['mobile_number'] ?? ""),
                    "email" => $deliveryAddress['email'] ?? ""
                ],
                "parcels" => $group['parcels'],
                "opt_in_rates" => [],
                "opt_in_time_based_rates" => [76],
                "declared_value" => 0.0,
                "collection_min_date" => gmdate("Y-m-d\TH:i:s\Z"),
                "collection_after" => "08:00",
                "collection_before" => "16:00",
                "delivery_min_date" => gmdate("Y-m-d\TH:i:s\Z"),
                "delivery_after" => "10:00",
                "delivery_before" => "17:00",
                "service_level_code" => "ECO",
                "notes" => "Rate calc"
            ];

            $ch = curl_init($apiUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer $apiKey",
                    "Content-Type: application/json"
                ],
                CURLOPT_POSTFIELDS => json_encode($payload)
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (!$response || $httpCode >= 400) {
                error_log("Courier Guy API error: HTTP $httpCode - " . $response);
                continue;
            }
            
            $data = json_decode($response, true);
            $rate = $data['rates'][0]['rate'] ?? 0;
            $totalRate += (float)$rate;
        }

        // Return calculated rate (0 if calculation failed - caller should handle fallback)
        return $totalRate;
    }
}
