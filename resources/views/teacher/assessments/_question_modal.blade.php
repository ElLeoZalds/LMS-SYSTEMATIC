<!-- Question Modal Partial -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-gray-800" id="questionModalLabel">Nueva Pregunta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="questionForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                <div id="methodContainer"></div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="question_text" class="text-gray-700 font-weight-bold">Pregunta</label>
                        <input type="text" class="form-control form-control-sm" id="question_text" name="question_text" required>
                    </div>
                    <div class="form-group mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="text-gray-700 font-weight-bold mb-0">Alternativas</label>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addAlternativeBtn">+ Añadir Opción</button>
                        </div>
                        <div id="alternativesContainer"></div>
                        <div class="form-group mt-3">
                            <label for="question-image" class="form-label small">Imagen (opcional)</label>
                            <input type="file" name="image" id="question-image" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted">Máx. 2 MB. Opcional: imagen para ilustrar la pregunta.</small>
                            <div id="question-image-preview" class="mt-2"></div>
                        </div>
                        <div class="text-muted small mt-2">Marca la alternativa correcta con el círculo.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">Guardar Pregunta</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const questionForm = document.getElementById('questionForm');
    const methodContainer = document.getElementById('methodContainer');
    const modalTitle = document.getElementById('questionModalLabel');
    const alternativesContainer = document.getElementById('alternativesContainer');
    const addAlternativeBtn = document.getElementById('addAlternativeBtn');
    const questionImageInput = document.getElementById('question-image');
    const maxImageSizeBytes = 2 * 1024 * 1024;
    let alternativeIndex = 0;

    function createAlternativeRow(index, textValue = '', isChecked = false) {
        const wrapper = document.createElement('div');
        wrapper.className = 'd-flex align-items-center mb-2 alternative-row';
        
        let radioChecked = isChecked ? 'checked' : '';
        
        wrapper.innerHTML = `
            <div class="input-group input-group-sm flex-grow-1 mr-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="radio" name="correct_alternative" value="${index}" aria-label="Correcta" ${radioChecked} required>
                    </div>
                </div>
                <input type="text" name="alternatives[${index}][text]" class="form-control form-control-sm" value="${textValue.replace(/"/g, '&quot;')}" placeholder="Opción ${index+1}" required>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger remove-alternative-btn">Eliminar</button>
        `;

        wrapper.querySelector('.remove-alternative-btn').addEventListener('click', function () {
            wrapper.remove();
            refreshAlternativeIndexes();
        });

        return wrapper;
    }

    function refreshAlternativeIndexes() {
        let tempIndex = 0;
        const rows = alternativesContainer.querySelectorAll('.alternative-row');
        rows.forEach((row) => {
            const radio = row.querySelector('input[type="radio"]');
            const textInput = row.querySelector('input[type="text"]');
            
            radio.value = tempIndex;
            textInput.name = 'alternatives[' + tempIndex + '][text]';
            textInput.placeholder = 'Opción ' + (tempIndex + 1);
            tempIndex++;
        });
        alternativeIndex = tempIndex;
    }

    function addAlternativeRow(textValue = '', isChecked = false) {
        const row = createAlternativeRow(alternativeIndex, textValue, isChecked);
        alternativesContainer.appendChild(row);
        alternativeIndex++;
    }

    if (addAlternativeBtn) {
        addAlternativeBtn.addEventListener('click', function () {
            addAlternativeRow();
        });
    }

    if (questionImageInput) {
        questionImageInput.addEventListener('change', function () {
            const file = this.files && this.files[0];

            if (!file) {
                return;
            }

            if (file.size > maxImageSizeBytes) {
                this.value = '';
                Swal.fire({
                    icon: 'warning',
                    title: 'Imagen demasiado grande',
                    text: 'La imagen debe pesar como máximo 2 MB.',
                    confirmButtonText: 'Entendido'
                });
            }
        });
    }

    document.addEventListener('click', function (event) {
        const button = event.target.closest('.add-question-btn, .edit-question-btn');
        if (!button) return;

        const activeMode = button.getAttribute('data-mode');
        const action = button.getAttribute('data-action');
        
        questionForm.action = action;
        questionForm.reset();
        alternativesContainer.innerHTML = '';
        alternativeIndex = 0;

        if (activeMode === 'create') {
            modalTitle.textContent = 'Nueva Pregunta';
            methodContainer.innerHTML = ''; 
            addAlternativeRow('', true); 
            addAlternativeRow('', false);
            document.getElementById('question-image-preview').innerHTML = '';
        } else if (activeMode === 'edit') {
            modalTitle.textContent = 'Editar Pregunta';
            methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">'; 
            
            const questionData = JSON.parse(button.getAttribute('data-question'));
            document.getElementById('question_text').value = questionData.text;

            if (questionData.alternatives && questionData.alternatives.length > 0) {
                questionData.alternatives.forEach((alt) => {
                    addAlternativeRow(alt.text, alt.is_correct == 1);
                });
            } else {
                addAlternativeRow('', true);
                addAlternativeRow('', false);
            }

            // show image preview if provided
            const imgPreview = document.getElementById('question-image-preview');
            imgPreview.innerHTML = '';
            const imageUrl = button.getAttribute('data-image') || '';
            if (imageUrl) {
                const img = document.createElement('img');
                img.src = imageUrl;
                img.style.maxWidth = '200px';
                img.className = 'img-thumbnail';
                imgPreview.appendChild(img);
            }
        }
    });

    // Validate alternatives count before submit
    questionForm.addEventListener('submit', function(e) {
        const file = questionImageInput && questionImageInput.files ? questionImageInput.files[0] : null;

        if (file && file.size > maxImageSizeBytes) {
            e.preventDefault();
            questionImageInput.value = '';
            Swal.fire({
                icon: 'warning',
                title: 'Imagen demasiado grande',
                text: 'La imagen debe pesar como máximo 2 MB.',
                confirmButtonText: 'Entendido'
            });
            return false;
        }

        const count = alternativesContainer.querySelectorAll('.alternative-row').length;
        if (count < 2 || count > 5) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Alternativas inválidas', text: 'Una pregunta debe tener entre 2 y 5 alternativas.' });
            return false;
        }
    });
});
</script>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form.swal-confirm').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const msg = form.getAttribute('data-message') || '¿Confirmar acción?';
            Swal.fire({
                title: 'Confirmar',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar'
            }).then((res) => {
                if (res.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>