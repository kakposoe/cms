<v-text-field
    id="testing"
    name="{{ $field->name }}"
    type="text"
    value="{{ $field->value }}"
    hint="{{ @$field->description }}"
    :error="{{ $errors->has( $field->name ) ? 'true' : 'false' }}"
    label="{{ $field->label }}"
></v-text-field>