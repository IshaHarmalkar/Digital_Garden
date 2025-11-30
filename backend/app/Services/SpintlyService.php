<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SpintlyService
{
    protected string $baseUrl;

    protected string $token;

    protected string $orgId;

    protected string $siteId;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.spintly.base_url');
        $this->token = config('services.spintly.access_token');
        $this->orgId = config('services.spintly.org_id');
        $this->siteId = config('services.spintly.site_id');
    }

    public function getUrl()
    {
        return "https://saams.api.spintly.com/organisationManagement/v2/integrator/organisations/{$this->orgId}/sites";
    }


    public function getOrgId()
    {
        return $this->orgId;
    }


    
    public function test()
    {
        return "{$this->baseUrl}/userManagement/integrator/v1/organisations/{$this->orgId}/formData?roles=roles";
    }

    // test
    public function getSites()
    {
        $url = "{$this->baseUrl}/organisationManagement/v2/integrator/organisations/{$this->orgId}/sites";   // hardcoded endpoint

        $body = [
            'pagination' => [
                'page' => 1,
                'perPage' => 40,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->token,
            'Accept' => 'application/json',
        ])->post($url, $body);

        if ($response->failed()) {
            return [
                'error' => 'API request failed',
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        return $response->json(); // return decoded JSON
    }



    public function getAccessPoints()
    {
        $url = "{$this->baseUrl}/organisationManagement/v1/integrator/organisations/{$this->orgId}/sites/{$this->siteId}/accessPoints/list";   // hardcoded endpoint

        $body = [
            'pagination' => [
                'page' => 1,
                'perPage' => 40,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->token,
            'Accept' => 'application/json',
        ])->post($url, $body);

        if ($response->failed()) {
            return [
                'error' => 'API request failed',
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        return $response->json();

    }



    // get roles
    public function getRoles()
    {
        $url = "{$this->baseUrl}/userManagement/integrator/v1/organisations/{$this->orgId}/formData?roles=roles";

        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json',
        ])->get($url);

        if ($response->failed()) {
            return [
                'error' => 'API request failed',
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        return $response->json();

    }



    // get roles
    public function getAllUSers()
    {
        $url = "{$this->baseUrl}/userManagement/integrator/v1/organisations/{$this->orgId}/users";

        $body = [
            'pagination' => [
                'page' => 1,
                'perPage' => 40,
            ],
            'filters' => [
                'userType' => ['active'],
                'terms' => [],
                'sites' => [],
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json',
        ])->post($url, $body);

        if ($response->failed()) {
            return [
                'error' => 'API request failed',
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        return $response->json();

    }



    public function createUser(array $data): array
    {
        $url = "{$this->baseUrl}/userManagement/integrator/v1/organisations/{$this->orgId}/users/create";

        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json',
        ])->post($url, $data);

        if ($response->failed()) {
            return [
                'error' => 'API request failed',
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        return $response->json();
    }



    public function updateUser(array $data): array
    {
        $url = "{$this->baseUrl}/userManagement/integrator/v1/organisations/{$this->orgId}/users/update";

        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json',
        ])
            ->patch($url, $data);
        if ($response->failed()) {
            return [
                'error' => 'API request failed',
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        return $response->json();
    }



    public function getUserPermission($userId)
    {
        $url = "{$this->baseUrl}/accessManagementV3/integrator/v1/organisations/{$this->orgId}/users/{$userId}/permissions";
       

        $body = [
            'sites' => [],
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json',
        ])->post($url, $body);

        if ($response->failed()) {
            return [
                'error' => 'API request failed',
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        return $response->json();

    }



    // Deactivate Users
    public function deactivateUser($userId)
    {
        $url = "{$this->baseUrl}/userManagement/integrator/v1/organisations/{$this->orgId}/users/update";

        $data = [
            'users' => [
                [
                    'id' => (int) $userId,
                    'deactivateUser' => true,
                ],
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json',
        ])->patch($url, $data);
        if ($response->failed()) {
            return [
                'error' => 'API request failed',
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        return $response->json();
    }



    // Activate Users
    public function activateUser($userId)
    {
        $url = "{$this->baseUrl}/userManagement/integrator/v1/organisations/{$this->orgId}/users/update";

        $data = [
            'users' => [
                [
                    'id' => (int) $userId,
                    'deactivateUser' => false,
                ],
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json',
        ])->patch($url, $data);
        if ($response->failed()) {
            return [
                'error' => 'API request failed',
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        return $response->json();
    }



    public function deleteUser($userId)
    {
        $url = "{$this->baseUrl}/userManagement/integrator/v1/organisations/{$this->orgId}/users/delete";

        $data = [
            'userIds' => [
                (int) $userId,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->token,
            'Accept' => 'application/json',
        ])->post($url, $data);
        if ($response->failed()) {
            return [
                'error' => 'API request failed',
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }

        return $response->json();
    }
}
