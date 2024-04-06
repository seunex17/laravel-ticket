<?php

    namespace App\Http\Controllers;

    use App\Models\User;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Response;

    class AuthController extends Controller {
        public function login(Request $request)
        {
            // Collect and validate user input
            $validator = \Validator::make($request->all(), [
                'email' => ['required', 'string', 'email'],
                'password' => ['required'],
            ]);

            if ($validator->fails())
            {
                $errors = $validator->errors()->all();

                return Response::json([
                    'error' => 'Validation error',
                    'message' => array_shift($errors)
                ], 400);
            }

            //* Attempt authentication
            if (Auth::attempt($request->all())) {
                $token = Auth::user()->createToken('simple-app');

                return Response::json([
                    'message' => 'Registration successful',
                    'access_token' => $token->plainTextToken,
                ]);
            }

            return Response::json([
                'message' => 'Authentication fails',
                'access_token' => 'Invalid login credentials',
            ], 400);
        }


        public function register(Request $request)
        : JsonResponse {
            // Collect and validate user input
            $validator = \Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required'],
            ]);

            if ($validator->fails())
            {
                $errors = $validator->errors()->all();

                return Response::json([
                    'error' => 'Validation error',
                    'message' => array_shift($errors)
                ], 400);
            }

            // Save user data to db
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('sample-app');

            return Response::json([
                'message' => 'Registration successful',
                'access_token' => $token->plainTextToken,
            ]);
        }
    }
