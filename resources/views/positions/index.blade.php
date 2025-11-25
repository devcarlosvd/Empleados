@extends('layouts.app')

@section('title', 'Lista de Cargos')

@section('content')
    <div style="padding: 2rem;">
        <!-- Header con título y botón -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-size: 1.75rem; font-weight: 600; color: #333; margin: 0;">
                <i class="fas fa-arrow-left me-3" style="cursor: pointer;" onclick="window.location='{{ route('home') }}'"></i>
                Cargos
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
                    <button class="btn" style="background: var(--primary-blue); color: white; border-radius: 8px; padding: 0.5rem 1.5rem;" onclick="openAddPositionModal()">
                        <i class="fas fa-briefcase me-2"></i> Agregar
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de cargos -->
        <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="border-radius: 12px; overflow: hidden;">
                        <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 1rem;">
                                <input type="checkbox" id="selectAllPositions" onchange="toggleSelectAllPositions(this)">
                                <span class="ms-2">Todos</span>
                            </th>
                            <th style="padding: 1rem;">
                                Nombre
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Buscar" onkeyup="filterPositionTable(0, this.value)" style="max-width: 150px;">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </th>
                            <th style="padding: 1rem;">
                                Identificación
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Buscar" onkeyup="filterPositionTable(1, this.value)" style="max-width: 150px;">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </th>
                            <th style="padding: 1rem;">
                                Área
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Buscar" onkeyup="filterPositionTable(2, this.value)" style="max-width: 150px;">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </th>
                            <th style="padding: 1rem;">
                                Cargo
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Buscar" onkeyup="filterPositionTable(3, this.value)" style="max-width: 150px;">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </th>
                            <th style="padding: 1rem;">
                                Rol
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Buscar" onkeyup="filterPositionTable(4, this.value)" style="max-width: 150px;">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </th>
                            <th style="padding: 1rem;">
                                Jefe
                                <div class="search-input-wrapper">
                                    <input type="text" class="form-control form-control-sm mt-2" placeholder="Buscar" onkeyup="filterPositionTable(5, this.value)" style="max-width: 150px;">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </th>
                            <th style="padding: 1rem; text-align: center;">Acciones</th>
                        </tr>
                        </thead>
                        <tbody id="positionTableBody">
                        @forelse($positions as $position)
                            <tr data-id="{{ $position->id }}">
                                <td style="padding: 1rem;">
                                    <input type="checkbox" class="position-checkbox" value="{{ $position->id }}">
                                </td>
                                <td style="padding: 1rem;">{{ $position->name }}</td>
                                <td style="padding: 1rem;">{{ $position->id }}</td>
                                <td style="padding: 1rem;">{{ $position->description }}</td>
                                <td style="padding: 1rem;">{{ $position->name }}</td>
                                <td style="padding: 1rem;">
                                    {{ $position->is_president ? 'Jefe' : 'Colaborador' }}
                                </td>
                                <td style="padding: 1rem;">
                                    {{ $position->is_president ? '-' : 'Jefe Superior' }}
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <button class="btn btn-sm" style="color: #ff9800;" onclick='editPosition(@json($position))' title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm" style="color: var(--primary-blue);" onclick="confirmDeletePosition({{ $position->id }})" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-position-form-{{ $position->id }}"
                                          action="{{ route('positions.destroy', $position) }}"
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
                                    <p class="text-muted">No hay cargos registrados</p>
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
                    Mostra de a {{ $positions->perPage() }}
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
                    <li class="page-item {{ $positions->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $positions->previousPageUrl() }}" style="border-radius: 8px; margin: 0 4px;">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    @for ($i = 1; $i <= $positions->lastPage(); $i++)
                        <li class="page-item {{ $positions->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $positions->url($i) }}" style="border-radius: 8px; margin: 0 4px;">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $positions->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $positions->nextPageUrl() }}" style="border-radius: 8px; margin: 0 4px;">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Modal para agregar/editar cargo -->
    <div class="modal fade" id="positionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 12px; border: none;">
                <div class="modal-header" style="background: var(--primary-blue); color: white; border-radius: 12px 12px 0 0;">
                    <h5 class="modal-title" id="positionModalTitle">Nuevo Cargo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <form id="positionForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="positionFormMethod" value="POST">
                        <input type="hidden" name="position_id" id="positionId">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Nombre</label>
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       id="position_name"
                                       placeholder="Buscar empleado"
                                       required
                                       style="border-radius: 8px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Identificación</label>
                                <input type="text"
                                       class="form-control"
                                       name="identification"
                                       id="position_identification"
                                       placeholder="Buscar número de identificación"
                                       required
                                       style="border-radius: 8px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Área</label>
                                <input type="text"
                                       class="form-control"
                                       name="area"
                                       id="position_area"
                                       placeholder="Escribe un área"
                                       required
                                       style="border-radius: 8px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Cargo</label>
                                <input type="text"
                                       class="form-control"
                                       name="description"
                                       id="position_description"
                                       placeholder="Escribe un cargo"
                                       style="border-radius: 8px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Rol</label>
                                <select class="form-select"
                                        name="role"
                                        id="position_role"
                                        required
                                        style="border-radius: 8px; padding: 0.75rem;">
                                    <option value="">Selecciona el rol del trabajador</option>
                                    <option value="Jefe">Jefe</option>
                                    <option value="Colaborador">Colaborador</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Jefe</label>
                                <input type="text"
                                       class="form-control"
                                       name="supervisor"
                                       id="position_supervisor"
                                       placeholder="Escribe un nombre"
                                       style="border-radius: 8px; padding: 0.75rem;">
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

    <!-- Modal de confirmación de eliminación individual -->
    <div class="modal fade" id="deletePositionConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
                <div class="modal-body text-center" style="padding: 3rem 2rem;">
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px;">
                            <i class="fas fa-trash" style="font-size: 2.5rem; color: white;"></i>
                        </div>
                    </div>

                    <h5 style="margin-bottom: 1rem; font-weight: 600; color: #1a1a1a; font-size: 1.5rem;">
                        Borrar cargo
                    </h5>

                    <p id="deletePositionMessage" style="color: #666; font-size: 1rem; margin-bottom: 2rem;">
                        ¿Está seguro de borrar este cargo?
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
                                onclick="executePositionDelete()"
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
                        Borrar cargos seleccionados
                    </h5>

                    <p id="deleteMultipleMessage" style="color: #666; font-size: 1rem; margin-bottom: 2rem;">
                        ¿Está seguro de borrar los cargos seleccionados?
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
        #deletePositionConfirmModal .modal-content,
        #deleteMultipleConfirmModal .modal-content {
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
    </style>
@endpush

@push('scripts')
    <script>
        let positionModal;
        let deletePositionModal;
        let deleteMultipleModal;
        let currentDeleteId = null;
        let positionsToDelete = [];

        $(document).ready(function() {
            positionModal = new bootstrap.Modal(document.getElementById('positionModal'));
            deletePositionModal = new bootstrap.Modal(document.getElementById('deletePositionConfirmModal'));
            deleteMultipleModal = new bootstrap.Modal(document.getElementById('deleteMultipleConfirmModal'));

            $('#positionForm').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const positionId = $('#positionId').val();
                const method = $('#positionFormMethod').val();
                let url = '{{ route("positions.store") }}';

                if (method === 'PUT' && positionId) {
                    url = `/positions/${positionId}`;
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        positionModal.hide();
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error al guardar el cargo. Por favor verifica los datos.');
                    }
                });
            });
        });

        function openAddPositionModal() {
            $('#positionModalTitle').text('Nuevo Cargo');
            $('#positionFormMethod').val('POST');
            $('#positionId').val('');
            $('#positionForm')[0].reset();
            positionModal.show();
        }

        function editPosition(position) {
            $('#positionModalTitle').text('Editar Cargo');
            $('#positionFormMethod').val('PUT');
            $('#positionId').val(position.id);
            $('#position_name').val(position.name);
            $('#position_identification').val(position.id);
            $('#position_area').val(position.description);
            $('#position_description').val(position.name);
            $('#position_role').val(position.is_president ? 'Jefe' : 'Colaborador');
            positionModal.show();
        }

        function confirmDeletePosition(positionId) {
            currentDeleteId = positionId;
            $('#deletePositionMessage').text('¿Está seguro de borrar este cargo?');
            deletePositionModal.show();
        }

        function executePositionDelete() {
            if (currentDeleteId) {
                document.getElementById('delete-position-form-' + currentDeleteId).submit();
            }
        }

        function confirmDeleteSelected() {
            const selected = $('.position-checkbox:checked').map(function() {
                return this.value;
            }).get();

            if (selected.length === 0) {
                // Mostrar modal de advertencia
                $('#deleteMultipleMessage').text('Por favor seleccione al menos un cargo');
                $('#deleteMultipleConfirmModal .btn-accept-delete').hide();
                $('#deleteMultipleConfirmModal .btn-cancel-delete').text('Entendido');
                deleteMultipleModal.show();
                return;
            }

            // Restaurar botones para eliminación normal
            $('#deleteMultipleConfirmModal .btn-accept-delete').show();
            $('#deleteMultipleConfirmModal .btn-cancel-delete').text('Cancelar');

            positionsToDelete = selected;
            const message = selected.length === 1
                ? '¿Está seguro de borrar 1 cargo seleccionado?'
                : `¿Está seguro de borrar ${selected.length} cargos seleccionados?`;

            $('#deleteMultipleMessage').text(message);
            deleteMultipleModal.show();
        }

        function executeMultipleDelete() {
            if (positionsToDelete.length > 0) {
                $.ajax({
                    url: '{{ route("positions.bulkDelete") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: positionsToDelete
                    },
                    success: function(response) {
                        deleteMultipleModal.hide();
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error al eliminar los cargos seleccionados');
                        console.error(xhr);
                    }
                });
            }
        }

        function toggleSelectAllPositions(checkbox) {
            $('.position-checkbox').prop('checked', checkbox.checked);
        }

        function filterPositionTable(columnIndex, searchValue) {
            const table = document.getElementById('positionTableBody');
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
