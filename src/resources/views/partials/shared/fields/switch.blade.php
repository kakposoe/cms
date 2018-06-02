<label for="{{ $field->name }}">{{ @$field->label }}</label>
<div class="switch">
    @foreach( $field->options as $value => $text )
    <label>
        <input {{ in_array( $value, array_keys( ( array ) @$field->value ) ) ? 'checked="checked"' : null }} type="checkbox"  name="{{ $field->name }}" value="true">
        {{ @$text }}
    </label>
    <br>
    @endforeach

    
    <br>
</div>
<app-switch-{{ $field->name }} inline-template>
    <v-switch
        :true-value="{{ in_array( $value, array_keys( ( array ) @$field->value ) ) ? 'true' : 'false' }}"
        v-model="{{ $field->name }}"
        name="{{ $field->name }}"
        label="{{ $field->label }}?"
    ></v-switch>
</app-switch-{{ $field->name }}>
@if ( $errors->has( $field->name ) )
<div class="invalid-feedback d-block">
    {{ $errors->first( $field->name ) }}
</div>
@else
<small class="form-text text-muted">{{ @$field->description }}</small>
@endif
<input name="_checkbox[]" value="{{ $field->name }}" type="hidden"/>
@push( 'vue.components' )
<script>
Vue.component( 'app-switch-{{ $field->name }}', {
    data() {
        return Object.assign({}, {
            {{ $field->name }} : ''
        })
    }
})
</script>
@endpush