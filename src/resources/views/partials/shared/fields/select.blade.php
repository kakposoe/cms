<app-select-{{ $field->name }} inline-template>
    <v-select
        :items="options"
        name="{{ $field->name }}"
        label="{{ $field->label }}"
        single-line
        v-model="selected"
        value="{{ @$field->value }}"
        hint="{{ @$field->description }}"
    ></v-select>
</app-select-{{ $field->name }}>
@push( 'vue.components' )
<script>

@php
$options    =   [];
foreach( $field->options as $value => $text ) {
    $options[]  =   compact( 'value', 'text' );
}
@endphp

var {{ $field->name }} = {
    options             :   @json( $options ),
    {{ $field->name }}  :   '',
    value               :   '{{ $field->value }}'
};

Vue.component( 'app-select-{{ $field->name }}', {
    data() {
        return Object.assign({
            selected : ''
        }, {{ $field->name }})
    },
    mounted() {
        this.options.forEach( ( option ) => {
            console.log( option, this.value );
            if ( option.value == this.value ) {
                this.selected   =   option;
            }
        })
        // console.log( this.selected, this.value );
    }
})
</script>
@endpush
