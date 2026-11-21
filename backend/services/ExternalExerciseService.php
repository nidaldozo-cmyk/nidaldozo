<?php

class ExternalExerciseService
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function searchExercises(string $term): array
    {
        $url = rtrim($this->config['base_url'], '/')
            . '/exerciseinfo/?'
            . http_build_query([
                'name__search' => $term,
                'language__code' => 'en',
                'format' => 'json',
            ]);

        $headers = [];
        if ($this->config['api_key'] !== '') {
            $headers[] = 'Authorization: Token ' . $this->config['api_key'];
        }

        $handle = curl_init($url);
        curl_setopt_array($handle, [
            CURLOPT_CONNECTTIMEOUT => $this->config['connect_timeout'],
            CURLOPT_TIMEOUT => $this->config['timeout'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $response = curl_exec($handle);
        $curlError = curl_errno($handle);
        $httpStatus = (int) curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        if ($curlError === CURLE_OPERATION_TIMEDOUT) {
            throw new Exception('External exercise API request timed out.', 504);
        }

        if ($response === false || $curlError !== 0) {
            throw new Exception('External exercise API request failed.', 502);
        }

        if ($httpStatus !== 200) {
            throw new Exception('External exercise API returned an unsuccessful status.', 502);
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['results']) || !is_array($data['results'])) {
            throw new Exception('External exercise API returned an invalid response.', 502);
        }

        $exercises = [];
        foreach ($data['results'] as $item) {
            $name = null;
            foreach ($item['translations'] ?? [] as $translation) {
                if (($translation['language'] ?? null) === 2) {
                    $name = $translation['name'] ?? null;
                    break;
                }
            }

            $equipmentNames = [];
            foreach ($item['equipment'] ?? [] as $equipment) {
                if (isset($equipment['name'])) {
                    $equipmentNames[] = $equipment['name'];
                }
            }

            $exercises[] = [
                'external_api_id' => $item['id'] ?? null,
                'name' => $name ?? ($item['translations'][0]['name'] ?? 'Unknown'),
                'muscle_group' => $item['category']['name'] ?? null,
                'equipment' => $equipmentNames === [] ? null : implode(', ', $equipmentNames),
                'image' => $item['images'][0]['image'] ?? null,
            ];
        }

        return $exercises;
    }

}
