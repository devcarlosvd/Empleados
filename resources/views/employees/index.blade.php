@extends('layouts.app')

@section('title', 'Lista de Empleados')

@section('content')
    <div style="padding: 2rem;">
        <!-- Header con título y botón -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-size: 1.75rem; font-weight: 600; color: #333; margin: 0;">
                <i class="fas fa-arrow-left me-3" style="cursor: pointer;" onclick="window.location='{{ route('home') }}'"></i>
                Empleados
            </h2>
        </div>

        <!-- Toolbar con acciones -->
        <div class="card mb-4" style="border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" style="border-radius: 8px;" onclick="confirmDeleteSelected()">
                            <i class="fas fa-trash me-2"></i> Borrar selección
                        </button>
                        <button class="btn btn-outline-primary" style="border-radius: 8px;">
                            <i class="fas fa-download me-2"></i> Descargar datos
                        </button>
                    </div>
                    <button class="btn" style="background: var(--primary-blue); color: white; border-radius: 8px; padding: 0.5rem 1.5rem;" onclick="openAddModal()">
                        <i class="fas fa-user-plus me-2"></i> Agregar
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de empleados -->
        <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="border-radius: 12px; overflow: hidden;">
                        <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 1rem;">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                                <span class="ms-2">Todos</span>
                            </th>
                            <th style="padding: 1rem;">
                                Nombre
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Buscar" onkeyup="filterTable(0, this.value)" style="max-width: 150px;">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </th>
                            <th style="padding: 1rem;">
                                Identificación
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Buscar" onkeyup="filterTable(1, this.value)" style="max-width: 150px;">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </th>
                            <th style="padding: 1rem;">
                                Dirección
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Buscar" onkeyup="filterTable(2, this.value)" style="max-width: 150px;">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </th>
                            <th style="padding: 1rem;">
                                Teléfono
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Buscar" onkeyup="filterTable(3, this.value)" style="max-width: 150px;">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </th>
                            <th style="padding: 1rem;">
                                Ciudad
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Buscar" onkeyup="filterTable(4, this.value)" style="max-width: 150px;">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </th>
                            <th style="padding: 1rem;">
                                Departamento
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Buscar" onkeyup="filterTable(5, this.value)" style="max-width: 150px;">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </th>
                            <th style="padding: 1rem; text-align: center;">Acciones</th>
                        </tr>
                        </thead>
                        <tbody id="employeeTableBody">
                        @forelse($employees as $employee)
                            <tr data-id="{{ $employee->id }}">
                                <td style="padding: 1rem;">
                                    <input type="checkbox" class="employee-checkbox" value="{{ $employee->id }}">
                                </td>
                                <td style="padding: 1rem;">{{ $employee->full_name }}</td>
                                <td style="padding: 1rem;">{{ $employee->identification }}</td>
                                <td style="padding: 1rem;">{{ $employee->address }}</td>
                                <td style="padding: 1rem;">{{ $employee->phone }}</td>
                                <td style="padding: 1rem;">{{ $employee->birthCity->name }}</td>
                                <td style="padding: 1rem;">{{ $employee->birthCity->country->name }}</td>
                                <td style="padding: 1rem; text-align: center;">
                                    <button class="btn btn-sm" style="color: #ff9800;" onclick='editEmployee(@json($employee))' title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm" style="color: var(--primary-blue);" onclick="confirmDelete({{ $employee->id }}, '{{ $employee->full_name }}')" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $employee->id }}"
                                          action="{{ route('employees.destroy', $employee) }}"
                                          method="POST"
                                          style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No hay empleados registrados</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: 8px;">
                    Mostra de a {{ $employees->perPage() }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?per_page=5">5</a></li>
                    <li><a class="dropdown-item" href="?per_page=10">10</a></li>
                    <li><a class="dropdown-item" href="?per_page=25">25</a></li>
                    <li><a class="dropdown-item" href="?per_page=50">50</a></li>
                </ul>
            </div>

            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item {{ $employees->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $employees->previousPageUrl() }}" style="border-radius: 8px; margin: 0 4px;">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    @for ($i = 1; $i <= $employees->lastPage(); $i++)
                        <li class="page-item {{ $employees->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $employees->url($i) }}" style="border-radius: 8px; margin: 0 4px;">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $employees->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $employees->nextPageUrl() }}" style="border-radius: 8px; margin: 0 4px;">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Modal para agregar/editar empleado -->
    <div class="modal fade" id="employeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 12px; border: none;">
                <div class="modal-header" style="background: var(--primary-blue); color: white; border-radius: 12px 12px 0 0;">
                    <h5 class="modal-title" id="modalTitle">Nuevo empleado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <form id="employeeForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <input type="hidden" name="employee_id" id="employeeId">
                        <input type="hidden" name="address" id="address" value="Sin dirección">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Nombres</label>
                                <input type="text"
                                       class="form-control"
                                       name="first_name"
                                       id="first_name"
                                       placeholder="Escribe el nombre de tu empleado"
                                       required
                                       style="border-radius: 8px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Apellidos</label>
                                <input type="text"
                                       class="form-control"
                                       name="last_name"
                                       id="last_name"
                                       placeholder="Escribe los apellidos de tu empleado"
                                       required
                                       style="border-radius: 8px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Identificación</label>
                                <input type="text"
                                       class="form-control"
                                       name="identification"
                                       id="identification"
                                       placeholder="Escribe un número de identificación"
                                       required
                                       style="border-radius: 8px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Teléfono</label>
                                <input type="text"
                                       class="form-control"
                                       name="phone"
                                       id="phone"
                                       placeholder="Escribe un número de teléfono"
                                       required
                                       style="border-radius: 8px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Ciudad</label>
                                <select class="form-select"
                                        name="birth_city_id"
                                        id="birth_city_id"
                                        required
                                        disabled
                                        style="border-radius: 8px; padding: 0.75rem;">
                                    <option value="">Selecciona una ciudad</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Departamento</label>
                                <select class="form-select"
                                        name="country_id"
                                        id="country_id"
                                        required
                                        style="border-radius: 8px; padding: 0.75rem;">
                                    <option value="">Selecciona un departamento</option>
                                    @foreach($countries ?? [] as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal"
                                    style="border-radius: 8px; padding: 0.5rem 1.5rem;">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="btn"
                                    style="background: var(--primary-blue); color: white; border-radius: 8px; padding: 0.5rem 1.5rem;">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación de empleado individual -->
    <div class="modal fade" id="deleteEmployeeConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
                <div class="modal-body text-center" style="padding: 3rem 2rem;">
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px;">
                            <i class="fas fa-trash" style="font-size: 2.5rem; color: white;"></i>
                        </div>
                    </div>

                    <h5 style="margin-bottom: 1rem; font-weight: 600; color: #1a1a1a; font-size: 1.5rem;">
                        Borrar empleado
                    </h5>

                    <p id="deleteEmployeeMessage" style="color: #666; font-size: 1rem; margin-bottom: 2rem;">
                        ¿Está seguro de borrar este empleado?
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button"
                                class="btn btn-cancel-delete"
                                data-bs-dismiss="modal"
                                style="background: #667eea; color: white; border: none; border-radius: 12px; padding: 0.75rem 2.5rem; font-weight: 500; min-width: 130px; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;">
                            Cancelar
                        </button>
                        <button type="button"
                                class="btn btn-accept-delete"
                                onclick="executeEmployeeDelete()"
                                style="background: #667eea; color: white; border: none; border-radius: 12px; padding: 0.75rem 2.5rem; font-weight: 500; min-width: 130px; box-shadow: 0 4px 12px rgba(118, 75, 162, 0.3); transition: all 0.3s ease;">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación múltiple -->
    <div class="modal fade" id="deleteMultipleConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
                <div class="modal-body text-center" style="padding: 3rem 2rem;">
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px;">
                            <i class="fas fa-trash" style="font-size: 2.5rem; color: white;"></i>
                        </div>
                    </div>

                    <h5 style="margin-bottom: 1rem; font-weight: 600; color: #1a1a1a; font-size: 1.5rem;">
                        Borrar empleados seleccionados
                    </h5>

                    <p id="deleteMultipleMessage" style="color: #666; font-size: 1rem; margin-bottom: 2rem;">
                        ¿Está seguro de borrar los empleados seleccionados?
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button"
                                class="btn btn-cancel-delete"
                                data-bs-dismiss="modal"
                                style="background: #667eea; color: white; border: none; border-radius: 12px; padding: 0.75rem 2.5rem; font-weight: 500; min-width: 130px; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;">
                            Cancelar
                        </button>
                        <button type="button"
                                class="btn btn-accept-delete"
                                onclick="executeMultipleDelete()"
                                style="background: #667eea; color: white; border: none; border-radius: 12px; padding: 0.75rem 2.5rem; font-weight: 500; min-width: 130px; box-shadow: 0 4px 12px rgba(118, 75, 162, 0.3); transition: all 0.3s ease;">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de error -->
    <div class="modal fade" id="errorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
                <div class="modal-body text-center" style="padding: 3rem 2rem;">
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: #FFC107; border-radius: 16px;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 2.5rem; color: white;"></i>
                        </div>
                    </div>

                    <h5 style="margin-bottom: 1rem; font-weight: 600; color: #1a1a1a; font-size: 1.5rem;">
                        Error
                    </h5>

                    <p style="color: #666; font-size: 1rem; margin-bottom: 2rem;">
                        Error al guardar el empleado. Por favor verifica los datos.
                    </p>

                    <div class="d-flex justify-content-center">
                        <button type="button"
                                class="btn btn-error-close"
                                data-bs-dismiss="modal"
                                style="background: #4339F2; color: white; border: none; border-radius: 12px; padding: 0.75rem 2.5rem; font-weight: 500; min-width: 130px; box-shadow: 0 4px 12px rgba(67, 57, 242, 0.3); transition: all 0.3s ease;">
                            Entendido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Estilos para los inputs de búsqueda con icono */
        .search-input-wrapper {
            position: relative;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .search-input-wrapper input {
            padding-right: 2rem !important;
        }

        .search-icon {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
            font-size: 0.875rem;
        }

        /* Efectos hover para los botones del modal */
        .btn-cancel-delete:hover,
        .btn-accept-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.25) !important;
        }

        .btn-cancel-delete:active,
        .btn-accept-delete:active {
            transform: translateY(0);
        }

        /* Animación del modal */
        #deleteEmployeeConfirmModal .modal-content,
        #deleteMultipleConfirmModal .modal-content,
        #errorModal .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Efectos hover para el botón de error */
        .btn-error-close:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(67, 57, 242, 0.4) !important;
        }

        .btn-error-close:active {
            transform: translateY(0);
        }
    </style>
@endpush

@push('scripts')
    <script>
        let employeeModal;
        let deleteEmployeeModal;
        let deleteMultipleModal;
        let errorModal;
        let employeeToDelete = null;
        let employeesToDelete = [];

        $(document).ready(function() {
            employeeModal = new bootstrap.Modal(document.getElementById('employeeModal'));
            deleteEmployeeModal = new bootstrap.Modal(document.getElementById('deleteEmployeeConfirmModal'));
            deleteMultipleModal = new bootstrap.Modal(document.getElementById('deleteMultipleConfirmModal'));
            errorModal = new bootstrap.Modal(document.getElementById('errorModal'));

            // Cargar ciudades cuando se selecciona un departamento
            $('#country_id').on('change', function() {
                loadCities($(this).val());
            });

            // Handle form submission
            $('#employeeForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const employeeId = $('#employeeId').val();
                const method = $('#formMethod').val();
                let url = '{{ route("employees.store") }}';

                if (method === 'PUT' && employeeId) {
                    url = `/employees/${employeeId}`;
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        employeeModal.hide();
                        location.reload();
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error al guardar el empleado. Por favor verifica los datos.';

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        // Actualizar el mensaje del modal de error
                        $('#errorModal p').html(errorMessage);
                        errorModal.show();
                    }
                });
            });
        });

        function loadCities(countryId, selectedCityId = null) {
            const citySelect = $('#birth_city_id');

            if (countryId) {
                citySelect.prop('disabled', true).html('<option value="">Cargando ciudades...</option>');

                $.ajax({
                    url: '/api/cities-by-country/' + countryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        citySelect.html('<option value="">Selecciona una ciudad</option>');
                        $.each(data, function(key, city) {
                            const selected = selectedCityId && city.id == selectedCityId ? 'selected' : '';
                            citySelect.append(`<option value="${city.id}" ${selected}>${city.name}</option>`);
                        });
                        citySelect.prop('disabled', false);
                    },
                    error: function() {
                        alert('Error al cargar las ciudades');
                    }
                });
            } else {
                citySelect.prop('disabled', true).html('<option value="">Primero seleccione un departamento</option>');
            }
        }

        function openAddModal() {
            $('#modalTitle').text('Nuevo empleado');
            $('#formMethod').val('POST');
            $('#employeeId').val('');
            $('#employeeForm')[0].reset();
            $('#birth_city_id').prop('disabled', true).html('<option value="">Selecciona una ciudad</option>');
            employeeModal.show();
        }

        function editEmployee(employee) {
            $('#modalTitle').text('Editar empleado');
            $('#formMethod').val('PUT');
            $('#employeeId').val(employee.id);
            $('#first_name').val(employee.first_name);
            $('#last_name').val(employee.last_name);
            $('#identification').val(employee.identification);
            $('#phone').val(employee.phone);
            $('#address').val(employee.address);
            $('#country_id').val(employee.birth_city.country_id);

            loadCities(employee.birth_city.country_id, employee.birth_city_id);
            employeeModal.show();
        }

        function confirmDelete(employeeId, employeeName) {
            employeeToDelete = employeeId;
            $('#deleteEmployeeMessage').text(`¿Está seguro de borrar a ${employeeName}?`);
            deleteEmployeeModal.show();
        }

        function executeEmployeeDelete() {
            if (employeeToDelete) {
                document.getElementById('delete-form-' + employeeToDelete).submit();
            }
        }

        function confirmDeleteSelected() {
            const selected = $('.employee-checkbox:checked').map(function() {
                return this.value;
            }).get();

            if (selected.length === 0) {
                // Mostrar modal de advertencia
                $('#deleteMultipleMessage').text('Por favor seleccione al menos un empleado');
                $('#deleteMultipleConfirmModal .btn-accept-delete').hide();
                $('#deleteMultipleConfirmModal .btn-cancel-delete').text('Entendido');
                deleteMultipleModal.show();
                return;
            }

            // Restaurar botones para eliminación normal
            $('#deleteMultipleConfirmModal .btn-accept-delete').show();
            $('#deleteMultipleConfirmModal .btn-cancel-delete').text('Cancelar');

            employeesToDelete = selected;
            const message = selected.length === 1
                ? '¿Está seguro de borrar 1 empleado seleccionado?'
                : `¿Está seguro de borrar ${selected.length} empleados seleccionados?`;

            $('#deleteMultipleMessage').text(message);
            deleteMultipleModal.show();
        }

        function executeMultipleDelete() {
            if (employeesToDelete.length > 0) {
                $.ajax({
                    url: '{{ route("employees.bulkDelete") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: employeesToDelete
                    },
                    success: function(response) {
                        deleteMultipleModal.hide();
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error al eliminar los empleados seleccionados');
                        console.error(xhr);
                    }
                });
            }
        }

        function toggleSelectAll(checkbox) {
            $('.employee-checkbox').prop('checked', checkbox.checked);
        }

        function filterTable(columnIndex, searchValue) {
            const table = document.getElementById('employeeTableBody');
            const rows = table.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                if (cells.length > columnIndex + 1) {
                    const cellValue = cells[columnIndex + 1].textContent || cells[columnIndex + 1].innerText;
                    if (cellValue.toLowerCase().indexOf(searchValue.toLowerCase()) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        }
    </script>
@endpush
