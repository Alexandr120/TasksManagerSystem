<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\UserAuthRepository;
use App\Services\UserAuthService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @var UserAuthRepository
     */
    public UserAuthRepository $userAuthRepository;

    /**
     * @var UserAuthService
     */
    public UserAuthService $userAuthService;

    public function __construct(UserAuthRepository $userAuthRepository, UserAuthService $userAuthService)
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);

        $this->userAuthRepository = $userAuthRepository;
        $this->userAuthService = $userAuthService;
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!$this->userAuthRepository->checkCredentials($credentials)) {
            return $this->sendResponse(Response::HTTP_BAD_REQUEST, 'Invalid user credentials!', Response::HTTP_BAD_REQUEST, $credentials);
        }

        $user = $this->userAuthRepository->user();

        return $this->sendResponse('success', 'User authorize successfully.', Response::HTTP_OK, [
            'user' => $user->name,
            'role' => $user->roles->first()->name,
            'authorisation' => [
                'token' => $this->userAuthRepository->createAuthToken(),
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $userData = $request->validated();

        unset($userData['password_confirmation']);

        try {
            return $this->sendResponse('success', 'User created successfully!', Response::HTTP_OK, [
                'user' => $this->userAuthService->createUser($userData)
            ]);

        } catch (\Exception $exception) {
            return $this->sendResponse('error', $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->userAuthRepository->logout();

        return $this->sendResponse('success', 'Logged out successfully.', Response::HTTP_OK);
    }
}
