<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Vehicle;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class VehicleController extends Controller
{
    /**
     * Create a new VehicleController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth_jwt:api', ['except' => ['getAll']]);
        $this->middleware('user_permission:vehicles')->except(['getAll', 'search']);
    }

    public function getOne(int $id)
    {
        $vehicle = Vehicle::find($id);
        if ($id && $vehicle) return response()->json(['vehicle' => $vehicle], 200);
        return response()->json(['vehicle' => null], 404);
    }

    public function getAll()
    {
        return response()->json(Vehicle::orderBy('price', 'desc')->paginate(15), 200);
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $vehicles = Vehicle::where(function ($query) use ($search) {
            $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")
                ->orWhere('model', 'like', "%{$search}%")
                ->orWhere('price', 'like', "%".floatval($search)."%");
        });

        return response()->json($vehicles->orderBy('price', 'desc')->paginate(), 200);
    }

    private function saveVehicle(Request $request, Vehicle $vehicle, array $data) : JsonResponse
    {
        try {
            if($request->name) $vehicle->name = $this->formatText($request->name);
            if ($request->description) $vehicle->description = $this->formatText($request->description);
            if ($request->brand) $vehicle->brand = $this->formatText($request->brand);
            if ($request->model) $vehicle->model = $this->formatText($request->model);
            if ($request->price) $vehicle->price = floatval($request->price);
            if (!is_null($request->active)) $vehicle->active = $request->active;

            if ($request->image instanceof UploadedFile) {
                $vehicle->image = $request->image->store('public');
                if(env('APP_ENV') === 'testing') $this->removeImage($vehicle->image);
            }

            $data['success'] = $vehicle->save();
            $data['vehicle'] = Vehicle::find($vehicle->id);

            return response()->json($data, 200);
        } catch (\Exception $e){
            $data['success'] = false;
            $data['errors'] = ['exception' => "O campo Nome do veículo dever ser único e válido."];
            return response()->json($data, 302);
        }
    }

    public function store(Request $request)
    {
        $validations = [
            'name' => 'required|min:1|max:255'.Rule::unique('vehicles', 'id')->ignore($request->id),
            'description' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => "nullable|file",
            'price' => "numeric",
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
        ];

        $messages = [
            "unique" => "O campo :attribute deve ser único.",
            "required" => "O campo :attribute é requerido.",
            "min" => "O campo :attribute deve conter no mínimo :min caracteres.",
            "max" => "O campo :attribute deve conter no máximo :max caracteres.",
            "string" => "O campo :attribute deve ser do tipo texto.",
            "int" => "O campo :attribute deve ser do tipo valor decimal.",
        ];

        $params = [
            "name" => "Nome do Veículo",
            "description" => "descrição do Veículo",
            "image" => "Imagem do Veículo",
            "brand" => "Marca do Veículo",
            "model" => "Modelo do Veículo",
            "price" => "Preço de Venda do Veículo"
        ];

        $validator = Validator::make($request->all(), $validations, $messages, $params);

        $data = [
            'errors' => $validator->errors(),
            'success' => false,
            'vehicle' => null,
        ];

        if (!$validator->fails()) {
            if(!$request->id) return $this->saveVehicle($request, new Vehicle(), $data);

            $vehicle = Vehicle::find($request->id);
            if ($vehicle) return $this->saveVehicle($request, $vehicle, $data);

            $data['errors'] = ['id' => 'Veículo não encontrado.'];
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
            ["id" => "Identificador da Veículo (ID)",]
        );

        $data = ['success' => false, 'errors' => $validator->errors()];

        $vehicle = Vehicle::find($request->id);
        if (!$validator->fails() && $vehicle instanceof Vehicle) {
            return response()->json(['success' => $vehicle->delete()], 200);
        }

        $data['errors'] = ['id' => 'Veículo não encontrado.', 'success' => false];
        return response()->json($data, 404);
    }
}
