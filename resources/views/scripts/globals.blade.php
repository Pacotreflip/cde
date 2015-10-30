<!-- App Globals -->
<script>
    window.App = {
        // Token CSRF de Laravel
        csrfToken: '{{ csrf_token() }}',

        // ID del Usuario Actual
        userId: {!! Auth::check() ? Auth::id() : 'null' !!},

        // Transformar los errores y asignarlos al formulario
        setErrorsOnForm: function (form, errors) {
            if (typeof errors === 'object') {
                form.errors = _.flatten(_.toArray(errors));
            } else {
                form.errors.push('Un error grave ocurrió. Por favor intente otra ves.');
            }
        }
    }
</script>