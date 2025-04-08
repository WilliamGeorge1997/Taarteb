<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ApiTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:test
                            {url : The API endpoint URL}
                            {--method=GET : HTTP method (GET, POST, PUT, DELETE, etc.)}
                            {--H|header=* : Headers in format "key:value"}
                            {--d|data=* : Request data in format "key:value"}
                            {--j|json : Send data as JSON}
                            {--f|form : Send data as form data}
                            {--b|body= : Raw request body}
                            {--verbose : Show detailed request and response}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quickly test API endpoints without using Postman';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = $this->argument('url');
        $method = strtoupper($this->option('method'));
        $headers = $this->parseKeyValuePairs($this->option('header'));
        $data = $this->parseKeyValuePairs($this->option('data'));
        $rawBody = $this->option('body');
        $asJson = $this->option('json');
        $asForm = $this->option('form');
        $verbose = $this->option('verbose');

        // Display request info if verbose
        if ($verbose) {
            $this->info("Making $method request to: $url");

            if (!empty($headers)) {
                $this->info('Headers:');
                foreach ($headers as $key => $value) {
                    $this->line("  $key: $value");
                }
            }

            if (!empty($data)) {
                $this->info('Data:');
                foreach ($data as $key => $value) {
                    $this->line("  $key: $value");
                }
            }

            if ($rawBody) {
                $this->info('Request Body:');
                $this->line($rawBody);
            }
        }

        // Create the request
        $request = Http::withHeaders($headers);

        // Make the request based on the method
        try {
            $startTime = microtime(true);
            $response = match ($method) {
                'GET' => $request->get($url, $data),
                'POST' => $this->makePostRequest($request, $url, $data, $rawBody, $asJson, $asForm),
                'PUT' => $this->makePutRequest($request, $url, $data, $rawBody, $asJson, $asForm),
                'DELETE' => $this->makeDeleteRequest($request, $url, $data, $rawBody, $asJson, $asForm),
                'PATCH' => $this->makePatchRequest($request, $url, $data, $rawBody, $asJson, $asForm),
                default => throw new \Exception("Unsupported HTTP method: $method"),
            };
            $endTime = microtime(true);
            $timeTaken = round(($endTime - $startTime) * 1000);

            // Display response
            $this->newLine();
            $this->info("Response (HTTP {$response->status()}) - {$timeTaken}ms");

            if ($verbose) {
                $this->info('Response Headers:');
                foreach ($response->headers() as $key => $value) {
                    $this->line("  $key: " . implode(', ', $value));
                }
            }

            $this->newLine();
            $body = $response->body();

            // Try to format JSON output
            if (json_decode($body) !== null) {
                $formattedJson = json_encode(json_decode($body), JSON_PRETTY_PRINT);
                $this->line($formattedJson);
            } else {
                $this->line($body);
            }

            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Parse key-value pairs from command options
     */
    private function parseKeyValuePairs(array $pairs): array
    {
        $result = [];
        foreach ($pairs as $pair) {
            if (strpos($pair, ':') !== false) {
                [$key, $value] = explode(':', $pair, 2);
                $result[trim($key)] = trim($value);
            }
        }
        return $result;
    }

    /**
     * Make a POST request
     */
    private function makePostRequest($request, $url, $data, $rawBody, $asJson, $asForm)
    {
        if ($rawBody) {
            return $request->withBody(
                $rawBody,
                $asJson ? 'application/json' : 'text/plain'
            )->post($url);
        } elseif ($asJson) {
            return $request->post($url, $data);
        } elseif ($asForm) {
            return $request->asForm()->post($url, $data);
        } else {
            return $request->post($url, $data);
        }
    }

    /**
     * Make a PUT request
     */
    private function makePutRequest($request, $url, $data, $rawBody, $asJson, $asForm)
    {
        if ($rawBody) {
            return $request->withBody(
                $rawBody,
                $asJson ? 'application/json' : 'text/plain'
            )->put($url);
        } elseif ($asJson) {
            return $request->put($url, $data);
        } elseif ($asForm) {
            return $request->asForm()->put($url, $data);
        } else {
            return $request->put($url, $data);
        }
    }

    /**
     * Make a DELETE request
     */
    private function makeDeleteRequest($request, $url, $data, $rawBody, $asJson, $asForm)
    {
        if ($rawBody) {
            return $request->withBody(
                $rawBody,
                $asJson ? 'application/json' : 'text/plain'
            )->delete($url);
        } elseif ($asJson) {
            return $request->delete($url, $data);
        } elseif ($asForm) {
            return $request->asForm()->delete($url, $data);
        } else {
            return $request->delete($url, $data);
        }
    }

    /**
     * Make a PATCH request
     */
    private function makePatchRequest($request, $url, $data, $rawBody, $asJson, $asForm)
    {
        if ($rawBody) {
            return $request->withBody(
                $rawBody,
                $asJson ? 'application/json' : 'text/plain'
            )->patch($url);
        } elseif ($asJson) {
            return $request->patch($url, $data);
        } elseif ($asForm) {
            return $request->asForm()->patch($url, $data);
        } else {
            return $request->patch($url, $data);
        }
    }
}
