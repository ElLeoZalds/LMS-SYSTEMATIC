@extends('layouts.auth')

@section('title', 'Registro - Systematic')

@section('content')

    <div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">

        <div class="col-12 col-md-8 col-lg-6 col-xl-5">

            <div class="text-center mb-4">
                <img src="{{ asset('images/Systematic_logo.png') }}" width="140" alt="Systematic">
                <h4 class="mt-3 brand">Crea tu cuenta</h4>
                <p class="text-muted mb-0">Regístrate para acceder a tus cursos y evaluaciones.</p>
            </div>

            <div class="card login-card p-4 shadow-sm">

                <form method="POST" action="{{ route('register.submit') }}">

                    @csrf

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3">Información personal</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombres</label>
                                <input type="text" name="first_names" value="{{ old('first_names') }}" class="form-control" placeholder="Tus nombres" required>
                                @error('first_names')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellidos</label>
                                <input type="text" name="last_names" value="{{ old('last_names') }}" class="form-control" placeholder="Tus apellidos" required>
                                @error('last_names')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3">Acceso a la cuenta</h6>
                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="nombre@ejemplo.com" required>
                            @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="********" required>
                            @error('password')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="********" required>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="terms" value="1" id="terms" required>
                        <label class="form-check-label" for="terms">
                            He leído y acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Términos y Condiciones</a> y la <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Política de Privacidad</a>.
                        </label>
                        @error('terms')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        Registrarse
                    </button>

                </form>

            </div>

            <p class="text-center mt-3 text-muted">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
            </p>

        </div>
    </div>

    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Términos y Condiciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Última actualización: Enero 2026</p>
                    <div class="small">
                        <h6 class="fw-bold"># Términos y Condiciones</h6>
                        <p>Estos Términos y Condiciones (los "Términos") regulan el acceso y uso del servicio de mensajería y comunicación vía WhatsApp API (el "Servicio") que ofrecemos a los Usuarios. Al utilizar el Servicio, el Usuario acepta estos Términos en su totalidad.</p>
                        <h6 class="fw-bold">1. Objeto del Servicio</h6>
                        <p>El Servicio permite la interacción entre el Usuario y nuestra plataforma mediante el canal oficial de WhatsApp provisto por Meta Platforms, Inc. incluyendo envíos de notificaciones, soporte, consultas, información comercial y mensajes transaccionales.</p>
                        <h6 class="fw-bold">2. Requisitos de Uso</h6>
                        <ul>
                            <li>El Usuario debe proporcionar información veraz y actualizada.</li>
                            <li>El Usuario debe contar con autorización o consentimiento previo para recibir comunicaciones.</li>
                            <li>El Servicio no podrá ser utilizado para fines ilícitos, fraudulentos o contrarios a las políticas comerciales de Meta.</li>
                        </ul>
                        <h6 class="fw-bold">3. Políticas de WhatsApp y Meta</h6>
                        <p>El Usuario reconoce y acepta que el uso del Servicio está sujeto a las políticas impuestas por Meta, entre ellas la Política Comercial de WhatsApp, la Política de Mensajería y sus actualizaciones. Meta podrá limitar, suspender o restringir el uso del canal sin responsabilidad alguna hacia nosotros.</p>
                        <h6 class="fw-bold">4. Consentimiento de Comunicación</h6>
                        <p>El Usuario autoriza expresamente el uso de WhatsApp como medio oficial de comunicación. El Usuario puede solicitar en cualquier momento dejar de recibir mensajes enviando una solicitud de salida ("opt-out") a través del mismo canal u otro medio indicado.</p>
                        <h6 class="fw-bold">5. Conductas Prohibidas</h6>
                        <p>Se encuentra prohibido:</p>
                        <ul>
                            <li>Enviar mensajes masivos no consentidos (spam)</li>
                            <li>Utilizar el Servicio para acoso, fraude o actividades ilegales</li>
                            <li>Transmitir contenido ofensivo, difamatorio o ilegal</li>
                        </ul>
                        <h6 class="fw-bold">6. Responsabilidad del Usuario</h6>
                        <p>El Usuario es responsable del uso que realice del Servicio, así como del contenido enviado, compartido o solicitado a través de WhatsApp.</p>
                        <h6 class="fw-bold">7. Limitación de Responsabilidad</h6>
                        <p>No garantizamos la disponibilidad permanente del Servicio debido a restricciones o cambios de políticas impuestas por Meta, interrupciones técnicas, fallos de red o fuerza mayor. No somos responsables por daños indirectos, comerciales o pérdida de datos.</p>
                        <h6 class="fw-bold">8. Datos Personales</h6>
                        <p>El tratamiento de datos personales se encuentra regulado por nuestra Política de Privacidad publicada en nuestro sitio web. El Usuario reconoce haber leído y aceptado dicha Política.</p>
                        <h6 class="fw-bold">9. Propiedad Intelectual</h6>
                        <p>Todas las marcas, logotipos, funcionalidades y contenidos relacionados con WhatsApp son propiedad de Meta Platforms, Inc. El Usuario no adquiere derechos sobre dichos activos.</p>
                        <h6 class="fw-bold">10. Suspensión o Terminación del Servicio</h6>
                        <p>Podemos suspender o cancelar el acceso al Servicio en caso de incumplimiento de estos Términos o ante requerimientos regulatorios o comerciales.</p>
                        <h6 class="fw-bold">11. Modificaciones</h6>
                        <p>Nos reservamos el derecho de modificar estos Términos en cualquier momento. Las modificaciones entrarán en vigencia a partir de su publicación en nuestro sitio web.</p>
                        <h6 class="fw-bold">12. Legislación Aplicable</h6>
                        <p>Estos Términos se rigen por la legislación vigente en el país donde operamos.</p>
                        <h6 class="fw-bold">13. Contacto</h6>
                        <p>Para cualquier consulta relacionada con el Servicio o estos Términos, el Usuario puede contactarnos mediante los canales oficiales publicados.</p>
                        <p class="mt-3">Al utilizar el Servicio, el Usuario declara haber leído, comprendido y aceptado estos Términos y Condiciones.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="privacyModal" tabindex="-1" role="dialog" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Política de Privacidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Última actualización: Enero 2026</p>
                    <div class="small">
                        <h6 class="fw-bold"># Política de Privacidad</h6>
                        <p>Esta Política de Privacidad describe cómo recopilamos, usamos, almacenamos y protegemos los datos personales procesados a través de nuestra integración con la API de WhatsApp (el "Servicio"). Al utilizar el Servicio, usted acepta el tratamiento de datos descrito en esta política.</p>
                        <h6 class="fw-bold">1. Responsable del Tratamiento</h6>
                        <p>Somos responsables del tratamiento de los datos que se procesan mediante el Servicio. Para consultas relacionadas con esta política, puede contactarnos a través de la información disponible en nuestro sitio web.</p>
                        <h6 class="fw-bold">2. Datos que Recopilamos</h6>
                        <p>Podemos recopilar y procesar los siguientes tipos de datos personales:</p>
                        <ul>
                            <li>Nombre y apellido</li>
                            <li>Número telefónico</li>
                            <li>Dirección de correo electrónico</li>
                            <li>Mensajes enviados mediante el Servicio</li>
                            <li>Identificadores de cuenta y metadatos de conversación</li>
                            <li>Datos comerciales relacionados con consultas o compras</li>
                        </ul>
                        <h6 class="fw-bold">3. Finalidades del Tratamiento</h6>
                        <p>Utilizamos los datos para:</p>
                        <ul>
                            <li>Gestionar comunicaciones con el Usuario a través de WhatsApp</li>
                            <li>Enviar notificaciones, actualizaciones o mensajes transaccionales</li>
                            <li>Ofrecer soporte y atención al cliente</li>
                            <li>Procesar pedidos o solicitudes comerciales</li>
                            <li>Cumplir obligaciones contractuales y legales</li>
                        </ul>
                        <h6 class="fw-bold">4. Base Legal para el Tratamiento</h6>
                        <p>El tratamiento se realiza con base en:</p>
                        <ul>
                            <li>Consentimiento explícito del Usuario</li>
                            <li>Ejecución de una relación contractual</li>
                            <li>Interés legítimo para fines comerciales lícitos</li>
                        </ul>
                        <h6 class="fw-bold">5. Consentimiento y Opt-in</h6>
                        <p>El Usuario declara haber otorgado consentimiento previo para recibir comunicaciones vía WhatsApp. El Usuario puede revocar el consentimiento en cualquier momento enviando una solicitud a través de nuestros canales oficiales.</p>
                        <h6 class="fw-bold">6. Transferencia de Datos a Terceros</h6>
                        <p>Utilizamos infraestructura y servicios provistos por Meta Platforms, Inc. para la entrega de mensajes. Las conversaciones pueden procesarse en servidores de Meta conforme a sus políticas de privacidad.</p>
                        <p><strong>No vendemos datos personales a terceros.</strong></p>
                        <h6 class="fw-bold">7. Conservación de Datos</h6>
                        <p>Los datos serán conservados mientras exista relación comercial o hasta que el Usuario solicite su eliminación, salvo obligaciones legales distintas.</p>
                        <h6 class="fw-bold">8. Derechos del Usuario</h6>
                        <p>El Usuario puede ejercer los derechos de acceso, rectificación, eliminación, oposición y portabilidad conforme a la legislación aplicable. Para ejercer estos derechos, el Usuario debe contactarnos.</p>
                        <h6 class="fw-bold">9. Seguridad</h6>
                        <p>Adoptamos medidas de seguridad técnicas y organizativas para proteger los datos contra acceso no autorizado, uso indebido, pérdida o destrucción.</p>
                        <h6 class="fw-bold">10. Menores de Edad</h6>
                        <p>El Servicio no está dirigido a menores de edad. No recopilamos datos de menores de forma deliberada.</p>
                        <h6 class="fw-bold">11. Modificaciones a esta Política</h6>
                        <p>Nos reservamos el derecho de actualizar esta Política en cualquier momento. La versión vigente será publicada en nuestro sitio web.</p>
                        <h6 class="fw-bold">12. Contacto</h6>
                        <p>Para solicitudes relacionadas con esta Política, por favor comuníquese con nosotros a través del canal publicado en nuestro sitio web.</p>
                        <p class="mt-3">Al utilizar el Servicio, el Usuario reconoce haber leído y aceptado esta Política de Privacidad.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

@endsection