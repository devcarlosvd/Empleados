<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Employee;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 10);
        $positions = Position::withCount('employees')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Obtener todos los empleados activos para el selector de jefe
        $employees = Employee::where('is_active', true)
            ->orderBy('first_name')
            ->get();

        return view('positions.index', compact('positions', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identification' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'description' => 'nullable|string',
            'role' => 'required|in:Jefe,Colaborador',
            'supervisor_id' => 'nullable|exists:employees,id',
        ], [
            'name.required' => 'El nombre del empleado es obligatorio',
            'identification.required' => 'La identificación es obligatoria',
            'area.required' => 'El área es obligatoria',
            'role.required' => 'El rol es obligatorio',
        ]);

        try {
            $position = Position::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? '',
                'is_president' => false,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cargo creado exitosamente',
                    'position' => $position
                ]);
            }

            return redirect()->route('positions.index')
                ->with('success', 'Cargo creado exitosamente');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el cargo'
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al crear el cargo'])
                ->withInput();
        }
    }

    public function update(Request $request, Position $position)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identification' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'description' => 'nullable|string',
            'role' => 'required|in:Jefe,Colaborador',
            'supervisor_id' => 'nullable|exists:employees,id',
        ], [
            'name.required' => 'El nombre del empleado es obligatorio',
            'identification.required' => 'La identificación es obligatoria',
            'area.required' => 'El área es obligatoria',
            'role.required' => 'El rol es obligatorio',
        ]);

        try {
            $position->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? '',
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cargo actualizado exitosamente'
                ]);
            }

            return redirect()->route('positions.index')
                ->with('success', 'Cargo actualizado exitosamente');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el cargo'
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al actualizar el cargo'])
                ->withInput();
        }
    }

    public function destroy(Position $position)
    {
        try {
            // Verificar si el cargo tiene empleados asignados
            if ($position->employees()->count() > 0) {
                return back()->withErrors([
                    'error' => 'No se puede eliminar un cargo que tiene empleados asignados'
                ]);
            }

            $position->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cargo eliminado exitosamente'
                ]);
            }

            return redirect()->route('positions.index')
                ->with('success', 'Cargo eliminado exitosamente');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el cargo'
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al eliminar el cargo']);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se seleccionaron cargos'
                ], 400);
            }

            // Verificar que ninguno tenga empleados asignados
            $positionsWithEmployees = Position::whereIn('id', $ids)
                ->has('employees')
                ->count();

            if ($positionsWithEmployees > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Algunos cargos tienen empleados asignados y no pueden ser eliminados'
                ], 400);
            }

            Position::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' cargo(s) eliminado(s) exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar los cargos'
            ], 500);
        }
    }
}
