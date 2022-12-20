<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Jobs\UserMailConfirmation;


class UserController extends Controller
{
    /**
     * Create a new UserhController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth_jwt:api')->except(['confirmMail']);
        $this->middleware('user_permission:users,is_self')->except(['confirmMail']);
    }

    public function getOne(int $id)
    {
        $user = User::find($id);
        if ($id && $user) return response()->json(['user' => $user], 200);
        return response()->json(['user' => null], 404);
    }

    public function getAll()
    {
        return response()->json(User::paginate(15), 200);
    }

    private function isSelfUser(Request $request) : bool
    {
        return $request->id == $request->authUser->id;
    }

    private function saveUser(Request $request, User $user, array $data) : JsonResponse
    {
        $lastEmail = null;
        if ($request->email && $user->id) {
            $user->email_verified_at = null;
            $lastEmail = $user->email;
        }

        if ($request->has('password')) $user->setPassword($request->password);
        if ($request->has('active')) $user->active = $request->active;
        if ($request->has('permissions')) $user->permissions = json_encode($request->permissions);

        if ($request->image instanceof UploadedFile) {
            $user->avatar = $request->image->store('public');
            if(env('APP_ENV') === 'testing') $this->removeImage($user->avatar);
        }

        $user->name = ucwords(trim($request->name));
        $user->email = trim($request->email);

        $data['success'] = $user->save();

        if ($user->wasRecentlyCreated === true || $user->email !== $lastEmail) UserMailConfirmation::dispatch($user);

        $data['user'] = $user;
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $requirePass = !$request->id || ($request->id && $request->password);
        $validations = [
            'name' => 'required|min:4|max:255',
            'email' => 'required|email|min:4|max:255|'. Rule::unique('users', 'email')->ignore($request->id),
            'password' => $requirePass ? "max:255|required" : "nullable"
        ];

        $messages = [
            "unique" => "O campo :attribute deve ser único.",
            "required" => "O campo :attribute é requerido.",
            "min" => "O campo :attribute deve conter no mínimo :min caracteres.",
            "max" => "O campo :attribute deve conter no máximo :max caracteres.",
        ];

        $params = [
            "name" => "Nome do Usuário",
            "email" => "Email do Usuário",
            "password" => "Senha do Usuário"
        ];

        $validator = Validator::make($request->all(), $validations, $messages, $params);

        $data = [
            'errors' => $validator->errors(),
            'success' => false,
            'user' => null,
        ];

        if (!$validator->fails()) {
            if(!$request->id) return $this->saveUser($request, new User(), $data);

            $user = User::find($request->id);
            if ($user) return $this->saveUser($request, $user, $data);

            $data['errors'] = ['id' => 'Usuário não encontrado.'];
            return response()->json($data, 404);
        }
        return response()->json($data, 302);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['id' => 'required'],
            ["required" => "O campo :attribute é requerido."],
            ["id" => "Identificador do Usuário (ID)",]
        );

        $data = ['success' => false, 'errors' => $validator->errors()];
        $user = User::find($request->id);

        if (!$validator->fails() && $user instanceof User) {
            if($this->isSelfUser($request)) {
                $data['errors'] = ['id' => 'Usuário não pode excluir a si.'];
                return response()->json($data, 400);
            }

            $data['success'] = $user->delete();
            return response()->json($data, 200);
        }

        $data['errors'] = ['id' => 'Usuário não encontrado.'];
        return response()->json($data, 404);
    }


    public function sendConfirmMail(int $id)
    {
        $user = User::find($id);
        $data = [
            'errors' => null,
            'success' => false,
        ];

        if($user) {
            if ($user->email_verified_at === null) UserMailConfirmation::dispatch($user);
            $data['success'] = true;
            return response()->json($data, 200);
        }

        $data['errros'] = ['id' => "Usuário não encontrado."];
        return response()->json($data, 404);
    }

    public function confirmMail(int $id, string $hash)
    {
        $user = User::find($id);
        if ($user && $user->compareConfirmationCode($hash)) {
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();
            return redirect('/cms')->with("status", "Email confirmado com sucesso!");
        }
        return redirect('/cms');
    }

    public function getPermissionsList()
    {
        return response()->json(User::DEFAULT_PERMISSIONS, 200);
    }

    public function setPermissions(Request $request)
    {
        $user = User::find($request->users_id);
        $data = [
            'errors' => null,
            'success' => false,
            'user' => null
        ];

        if($user && $request->permissions) {
            $user->setPermissions($request->permissions);
            $data['success'] = true;
            $data['user'] = $user;
            return response()->json($data, 200);
        }
        $data['errros'] = ['users_id' => "Usuário não encontrado."];
        return response()->json($data, 404);
    }
}
