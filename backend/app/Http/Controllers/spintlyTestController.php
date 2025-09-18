<?php

namespace App\Http\Controllers;

use App\Services\SpintlyService;
use Illuminate\Http\Request;

class SpintlyTestController extends Controller
{
    protected SpintlyService $spintly;

    public function __construct(SpintlyService $spintly)
    {
        $this->spintly = $spintly;
    }

    public function fetchSites()
    {
        $response = $this->spintly->getSites();

        return response()->json($response);
    }

    public function fetchAccessPoints()
    {
        $response = $this->spintly->getAccessPoints();

        return response()->json($response);
    }

    public function fetchRoles()
    {
        $response = $this->spintly->getRoles();

        return response()->json($response);
    }

    public function fetchAllUsers()
    {
        $response = $this->spintly->getAllUSers();

        return response()->json($response);
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'roles' => 'required|array',
            'adminOfSites' => 'array',
            'devicelock' => 'boolean',
            'accessData' => 'required|array',
            'homeSiteId' => 'required|integer',
            'terms' => 'array',
            'employeeCode' => 'nullable|string',
            'reportingTo' => 'nullable|integer',
            'probationPeriod' => 'nullable|integer',
            'joiningDate' => 'nullable|date',
            'credentialId' => 'nullable|integer',
        ]);

        $response = $this->spintly->createUser($validated);

        return response()->json($response);
    }

    public function updateUser(Request $request)
    {
        $response = $this->spintly->updateUser($request->all());

        return response()->json($response);
    }

    // Fetch user permissions
    public function fetchUserPermissions($userId)
    {
        $response = $this->spintly->getUserPermission($userId);

        return response()->json($response);
    }

    // deativate users
    public function deactivateUser($userId)
    {
        $response = $this->spintly->deactivateUser($userId);

        return response()->json($response);
    }

    // activate users
    public function activateUser($userId)
    {
        $response = $this->spintly->activateUser($userId);

        return response()->json($response);
    }

    public function deleteUser($userId)
    {
        $response = $this->spintly->deleteUser($userId);

        return response()->json($response);
    }
}
