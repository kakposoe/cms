@push( 'vue.components' )
<script>
	const menus = @json($menus)
</script>
<script src="{{ asset( 'tendoo/js/dashboard/menu.component.js' ) }}"></script>
@endpush 
@verbatim
<app-menu inline-template>
	<v-navigation-drawer :width="250" fixed :clipped="$vuetify.breakpoint.lgAndUp" app v-model="drawer">
		<v-list>
			<template v-for="menu in menus">
				<v-list-group  v-model="menu.active" :key="menu.text" :prepend-icon="menu.icon" :append-icon="menu.childrens ? 'keyboard_arrow_down' : ''" no-action>
					<v-list-tile v-if="menu.childrens" slot="activator">
						<v-list-tile-content>
							<v-list-tile-title>{{ menu.text }}</v-list-tile-title>
						</v-list-tile-content>
					</v-list-tile>
					<v-list-tile :href="menu.href" v-else slot="activator">
						<v-list-tile-content>
							<v-list-tile-title>{{ menu.text }}</v-list-tile-title>
						</v-list-tile-content>
					</v-list-tile>
					<v-list-tile :href="children.href" v-for="children in menu.childrens" :key="children.text" @click="">
						<v-list-tile-content>
							<v-list-tile-title>{{ children.text }}</v-list-tile-title>
						</v-list-tile-content>
						<v-list-tile-action>
							<v-icon>{{ children.icon }}</v-icon>
						</v-list-tile-action>
					</v-list-tile>
				</v-list-group>
			</template>
		</v-list>
	</v-navigation-drawer>
</app-menu>
@endverbatim
@push( 'vue.components' )
<script>
Vue.component( 'app-menu', {
    mounted() {
        TendooEvent.$on( 'aside.toggle', drawer => {
			this.drawer	= drawer;
		});
    },
    data() {
        return {
            menus,
			drawer: false
        }
	},
	computed: {
		drawer() {
			console.log( OptionsData.drawer );
			return OptionsData.drawer;
		}
	}
});
</script>
@endpush