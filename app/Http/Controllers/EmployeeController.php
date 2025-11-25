<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Country;
use App\Models\City;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 10);
        $employees = Employee::with(['birthCity.country', 'currentPositions', 'supervisor'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $countries = Country::orderBy('name')->get();

        return view('employees.index', compact('employees', 'countries'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
        $supervisors = Employee::where('is_active', true)
            ->orderBy('first_name')
            ->get();

        return view('employees.create', compact('countries', 'positions', 'supervisors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'identification' => 'required|string|unique:employees,identification|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'country_id' => 'required|exists:countries,id',
            'birth_city_id' => 'required|exists:cities,id',
            'supervisor_id' => 'nullable|exists:employees,id',
            'positions' => 'nullable|array',
            'positions.*' => 'exists:positions,id',
        ], [
            'first_name.required' => 'El nombre es obligatorio',
            'last_name.required' => 'El apellido es obligatorio',
            'identification.required' => 'La identificación es obligatoria',
            'identification.unique' => 'Esta identificación ya está registrada',
            'address.required' => 'La dirección es obligatoria',
            'phone.required' => 'El teléfono es obligatorio',
            'country_id.required' => 'Debe seleccionar un país',
            'birth_city_id.required' => 'Debe seleccionar una ciudad',
        ]);

        try {
            $employee = Employee::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'identification' => $validated['identification'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'birth_city_id' => $validated['birth_city_id'],
                'supervisor_id' => $validated['supervisor_id'] ?? null,
            ]);

            // Asociar cargos si existen
            if (isset($request->positions)) {
                foreach ($request->positions as $positionId) {
                    $employee->positions()->attach($positionId, [
                        'start_date' => now(),
                        'is_current' => true,
                    ]);
                }
            }

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Empleado creado exitosamente']);
            }

            return redirect()->route('employees.index')
                ->with('success', 'Empleado creado exitosamente');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error al crear el empleado'], 500);
            }
            return back()->withErrors(['error' => 'Error al crear el empleado: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(Employee $employee)
    {
        $employee->load(['birthCity.country', 'currentPositions']);
        $countries = Country::orderBy('name')->get();
        $cities = City::where('country_id', $employee->birthCity->country_id)->orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
        $supervisors = Employee::where('is_active', true)
            ->where('id', '!=', $employee->id)
            ->orderBy('first_name')
            ->get();

        return view('employees.edit', compact('employee', 'countries', 'cities', 'positions', 'supervisors'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'identification' => 'required|string|max:255|unique:employees,identification,' . $employee->id,
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'birth_city_id' => 'required|exists:cities,id',
        ], [
            'first_name.required' => 'El nombre es obligatorio',
            'last_name.required' => 'El apellido es obligatorio',
            'identification.required' => 'La identificación es obligatoria',
            'identification.unique' => 'Esta identificación ya está registrada',
            'address.required' => 'La dirección es obligatoria',
            'phone.required' => 'El teléfono es obligatorio',
            'birth_city_id.required' => 'Debe seleccionar una ciudad',
        ]);

        try {
            $employee->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'identification' => $validated['identification'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'birth_city_id' => $validated['birth_city_id'],
            ]);

            // Actualizar cargos si existen nuevos cargos (opcional, solo si usas positions en el futuro)
            // Este código está comentado porque no se usa en tu formulario actual
            /*
            if (isset($request->positions)) {
                $currentPositions = $employee->positions()
                    ->wherePivot('is_current', true)
                    ->get();

                foreach ($currentPositions as $position) {
                    $employee->positions()->updateExistingPivot($position->id, [
                        'is_current' => false,
                        'end_date' => now()
                    ]);
                }

                $syncData = [];
                foreach ($request->positions as $positionId) {
                    $syncData[$positionId] = [
                        'start_date' => now(),
                        'is_current' => true,
                        'end_date' => null,
                    ];
                }
                $employee->positions()->syncWithoutDetaching($syncData);
            }
            */

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Empleado actualizado exitosamente']);
            }

            return redirect()->route('employees.index')
                ->with('success', 'Empleado actualizado exitosamente');
        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error al actualizar empleado: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el empleado: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al actualizar el empleado: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Employee $employee)
    {
        try {
            // Verificar si tiene subordinados activos
            if ($employee->subordinates()->where('is_active', true)->exists()) {
                return back()->withErrors(['error' => 'No se puede eliminar un empleado que tiene colaboradores activos']);
            }

            $employee->is_active = false;
            $employee->save();
            $employee->delete(); // Soft delete

            return redirect()->route('employees.index')
                ->with('success', 'Empleado eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar el empleado: ' . $e->getMessage()]);
        }
    }

    // Método para obtener ciudades por país (AJAX)
    public function getCitiesByCountry($countryId)
    {
        $cities = City::where('country_id', $countryId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:employees,id'
        ]);

        try {
            Employee::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Empleados eliminados correctamente',
                'count' => count($request->ids)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar los empleados'
            ], 500);
        }
    }
}
