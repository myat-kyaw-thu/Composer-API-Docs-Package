<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
  protected AuthService $authService;

  public function __construct(AuthService $authService)
  {
    $this->authService = $authService;
  }

  /**
   * Register a new user.
   *
   * @param RegisterRequest $request
   * @return JsonResponse
   */
  public function register(RegisterRequest $request): JsonResponse
  {
    try {
      $result = $this->authService->register($request->validated(), $request);

      return response()->json([
        'data' => [
          'user' => [
            'id' => $result['user']->id,
            'name' => $result['user']->name,
            'email' => $result['user']->email,
            'created_at' => $result['user']->created_at,
          ],
          'token' => $result['token'],
          'token_id' => $result['token_id'],
        ],
        'meta' => [
          'timestamp' => now()->toISOString(),
        ],
      ], 201);
    } catch (\Exception $e) {
      return response()->json([
        'error' => [
          'message' => 'Registration failed',
          'code' => 'registration_failed',
          'details' => $e->getMessage(),
        ],
      ], 500);
    }
  }

  /**
   * Login user and return token.
   *
   * @param LoginRequest $request
   * @return JsonResponse
   */
  public function login(LoginRequest $request): JsonResponse
  {
    try {
      $result = $this->authService->login($request->validated(), $request);

      return response()->json([
        'data' => [
          'user' => [
            'id' => $result['user']->id,
            'name' => $result['user']->name,
            'email' => $result['user']->email,
            'email_verified_at' => $result['user']->email_verified_at,
          ],
          'token' => $result['token'],
          'token_id' => $result['token_id'],
        ],
        'meta' => [
          'timestamp' => now()->toISOString(),
        ],
      ]);
    } catch (ValidationException $e) {
      return response()->json([
        'error' => [
          'message' => 'Invalid credentials',
          'code' => 'invalid_credentials',
          'details' => $e->errors(),
        ],
      ], 401);
    } catch (\Exception $e) {
      return response()->json([
        'error' => [
          'message' => 'Login failed',
          'code' => 'login_failed',
          'details' => $e->getMessage(),
        ],
      ], 500);
    }
  }

  /**
   * Logout current user token.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function logout(Request $request): JsonResponse
  {
    try {
      $success = $this->authService->logout($request);

      if ($success) {
        return response()->json(null, 204);
      }

      return response()->json([
        'error' => [
          'message' => 'No active token found',
          'code' => 'no_active_token',
        ],
      ], 400);
    } catch (\Exception $e) {
      return response()->json([
        'error' => [
          'message' => 'Logout failed',
          'code' => 'logout_failed',
          'details' => $e->getMessage(),
        ],
      ], 500);
    }
  }

  /**
   * Logout from all devices.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function logoutAll(Request $request): JsonResponse
  {
    try {
      $deletedTokens = $this->authService->logoutAll($request);

      return response()->json([
        'data' => [
          'message' => 'Successfully logged out from all devices',
          'tokens_revoked' => $deletedTokens,
        ],
        'meta' => [
          'timestamp' => now()->toISOString(),
        ],
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'error' => [
          'message' => 'Logout from all devices failed',
          'code' => 'logout_all_failed',
          'details' => $e->getMessage(),
        ],
      ], 500);
    }
  }

  /**
   * Get authenticated user profile.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function me(Request $request): JsonResponse
  {
    try {
      $profile = $this->authService->getUserProfile($request->user(), $request);

      return response()->json([
        'data' => [
          'user' => [
            'id' => $profile['user']->id,
            'name' => $profile['user']->name,
            'email' => $profile['user']->email,
            'email_verified_at' => $profile['user']->email_verified_at,
            'meta' => $profile['user']->meta,
            'created_at' => $profile['user']->created_at,
            'updated_at' => $profile['user']->updated_at,
          ],
          'roles' => $profile['roles'],
          'permissions' => $profile['permissions'],
          'active_tokens' => $profile['active_tokens'],
        ],
        'meta' => [
          'timestamp' => now()->toISOString(),
        ],
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'error' => [
          'message' => 'Failed to retrieve user profile',
          'code' => 'profile_retrieval_failed',
          'details' => $e->getMessage(),
        ],
      ], 500);
    }
  }
}
