@extends('layouts.app')

@section('title', 'Bienvenida - Psico Alianza')

@section('content')
    <div class="welcome-section">
        <h1 class="welcome-title">Bienvenida!</h1>
        <h2 class="welcome-name">Elisa Gómez</h2>

        <p class="welcome-description">
            Añade los datos personales de tus empleados y después agrega su cargo en tu empresa
        </p>

        <a href="#" class="start-button" onclick="openEmployeeModal(event)">
            <div class="start-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="start-text">Empieza aquí</div>
        </a>

        <div class="welcome-illustration">
            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 400'%3E%3C!-- Desk --%3E%3Crect x='100' y='300' width='400' height='100' fill='%23e0e0e0' rx='10'/%3E%3C!-- Woman sitting --%3E%3Cellipse cx='300' cy='250' rx='60' ry='80' fill='%23f5f5f5'/%3E%3Ccircle cx='300' cy='200' r='30' fill='%23333'/%3E%3Crect x='280' y='240' width='40' height='60' fill='%23666' rx='5'/%3E%3C!-- Laptop --%3E%3Crect x='250' y='280' width='100' height='60' fill='%23333' rx='5'/%3E%3Crect x='260' y='290' width='80' height='40' fill='%234339F2'/%3E%3C!-- Plants --%3E%3Cellipse cx='450' cy='250' rx='30' ry='40' fill='%2366bb6a'/%3E%3Crect x='440' y='260' width='20' height='40' fill='%23795548'/%3E%3C!-- Network icons --%3E%3Ccircle cx='150' cy='200' r='20' fill='%234339F2' opacity='0.5'/%3E%3Ccircle cx='120' cy='180' r='10' fill='%234339F2' opacity='0.3'/%3E%3Ccircle cx='180' cy='190' r='15' fill='%234339F2' opacity='0.4'/%3E%3Cline x1='150' y1='200' x2='120' y2='180' stroke='%234339F2' stroke-width='2'/%3E%3Cline x1='150' y1='200' x2='180' y2='190' stroke='%234339F2' stroke-width='2'/%3E%3C/svg%3E" alt="Ilustración de trabajo">
        </div>
    </div>

    <!-- Modal para nuevo empleado -->
    <div class="modal fade" id="employeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 12px; border: none;">
                <div class="modal-header" style="background: var(--primary-blue); color: white; border-radius: 12px 12px 0 0;">
                    <h5 class="modal-title">Nuevo empleado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <form id="employeeFormModal" method="POST">
                        @csrf
                        <!-- Campo oculto para la dirección (requerido por el backend) -->
                        <input type="hidden" name="address" value="Sin dirección">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Nombres</label>
                                <input type="text"
                                       class="form-control"
                                       name="first_name"
                                       id="first_name_modal"
                                       placeholder="Escribe el nombre de tu empleado"
                                       required
                                       style="border-radius: 8px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Apellidos</label>
                                <input type="text"
                                       class="form-control"
                                       name="last_name"
                                       id="last_name_modal"
                                       placeholder="Escribe los apellidos de tu empleado"
                                       required
                                       style="border-radius: 8px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Identificación</label>
                                <input type="text"
                                       class="form-control"
                                       name="identification"
                                       id="identification_modal"
                                       placeholder="Escribe un número de identificación"
                                       required
                                       style="border-radius: 8px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Teléfono</label>
                                <input type="text"
                                       class="form-control"
                                       name="phone"
                                       id="phone_modal"
                                       placeholder="Escribe un número de teléfono"
                                       required
                                       style="border-radius: 8px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Ciudad</label>
                                <select class="form-select"
                                        name="birth_city_id"
                                        id="city_select_modal"
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
                                        id="country_select_modal"
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

    <!-- Modal de éxito -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
                <div class="modal-body text-center" style="padding: 3rem 2rem;">
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: linear-gradient(135deg, #4CAF50 0%, #66bb6a 100%); border-radius: 16px;">
                            <i class="fas fa-check" style="font-size: 2.5rem; color: white;"></i>
                        </div>
                    </div>

                    <h5 style="margin-bottom: 1rem; font-weight: 600; color: #1a1a1a; font-size: 1.5rem;">
                        ¡Empleado guardado!
                    </h5>

                    <p style="color: #666; font-size: 1rem; margin-bottom: 2rem;">
                        El empleado se ha registrado correctamente
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button"
                                class="btn"
                                data-bs-dismiss="modal"
                                style="background: #4CAF50; color: white; border: none; border-radius: 12px; padding: 0.75rem 2.5rem; font-weight: 500; min-width: 130px; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3); transition: all 0.3s ease;">
                            Continuar
                        </button>
                        <button type="button"
                                onclick="window.location='{{ route('employees.index') }}'"
                                class="btn"
                                style="background: var(--primary-blue); color: white; border: none; border-radius: 12px; padding: 0.75rem 2.5rem; font-weight: 500; min-width: 130px; box-shadow: 0 4px 12px rgba(67, 57, 242, 0.3); transition: all 0.3s ease;">
                            Ver empleados
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

                    <p id="errorMessage" style="color: #666; font-size: 1rem; margin-bottom: 2rem;">
                        Error al guardar el empleado. Por favor verifica los datos.
                    </p>

                    <div class="d-flex justify-content-center">
                        <button type="button"
                                class="btn"
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

@push('scripts')
    <script>
        let employeeModal;
        let successModal;
        let errorModal;

        $(document).ready(function() {
            // Inicializar modales
            employeeModal = new bootstrap.Modal(document.getElementById('employeeModal'));
            successModal = new bootstrap.Modal(document.getElementById('successModal'));
            errorModal = new bootstrap.Modal(document.getElementById('errorModal'));

            // Cargar ciudades cuando se selecciona un departamento
            $('#country_select_modal').on('change', function() {
                loadCities($(this).val());
            });

            // Manejar envío del formulario con AJAX
            $('#employeeFormModal').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: '{{ route("employees.store") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        employeeModal.hide();
                        $('#employeeFormModal')[0].reset();
                        $('#city_select_modal').prop('disabled', true).html('<option value="">Selecciona una ciudad</option>');
                        successModal.show();
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error al guardar el empleado. Por favor verifica los datos.';

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        $('#errorMessage').html(errorMessage);
                        errorModal.show();
                    }
                });
            });
        });

        function openEmployeeModal(event) {
            event.preventDefault();
            employeeModal.show();
        }

        function loadCities(countryId) {
            const citySelect = $('#city_select_modal');

            if (countryId) {
                citySelect.prop('disabled', true).html('<option value="">Cargando ciudades...</option>');

                $.ajax({
                    url: '/api/cities-by-country/' + countryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        citySelect.html('<option value="">Selecciona una ciudad</option>');
                        $.each(data, function(key, city) {
                            citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                        });
                        citySelect.prop('disabled', false);
                    },
                    error: function() {
                        citySelect.html('<option value="">Error al cargar ciudades</option>');
                        $('#errorMessage').text('Error al cargar las ciudades. Por favor intenta de nuevo.');
                        errorModal.show();
                    }
                });
            } else {
                citySelect.prop('disabled', true).html('<option value="">Primero seleccione un departamento</option>');
            }
        }
    </script>
@endpush
