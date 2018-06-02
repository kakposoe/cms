<label for="{{ $field->name }}">{{ @$field->label }}</label>
<div class="checkbox">
    @foreach( $field->options as $value => $text )
    <label>
        <input {{ in_array( $value, ( array ) @$field->value ) ? 'checked="checked"' : null }} type="checkbox"  name="{{ $field->name }}" value="{{ $value }}">
        {{ @$text }}
    </label>
    @endforeach

    @if ( $errors->has( $field->name ) )
    <div class="invalid-feedback d-block">
        {{ $errors->first( $field->name ) }}
    </div>
    @else
    <small class="form-text text-muted">{{ @$field->description }}</small>
    @endif
    <br>
</div>
<app-checkbox-{{ $field->name }} inline-template>
    <v-checkbox
    v-model="{{ $field-name }}"
        name="{{ $field->name }}"
        label="{{ $field->label }}?"
    ></v-checkbox>
</app-checkbox-{{ $field->name }}>
<input name="_checkbox[]" value="{{ $field->name }}" type="hidden"/>
@push( 'vue.components' )
<script>
Vue.component( 'app-checkbox-{{ $field->name }}', {
    data() {
        return {
            return Object.assign({}, {
                {{ $field->name }} : ''
            })
        }
    },
    mounted() {
        alert( 'ok' );
    }
})
</script>
@endpush
