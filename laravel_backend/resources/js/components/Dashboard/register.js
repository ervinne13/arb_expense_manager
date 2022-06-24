
import Vue from "vue"

export const registerDashboardComponents = () => {
    Vue.component(
        'HeaderItems',
        require('./HeaderItems.vue').default
    );
    Vue.component(
        'SidebarItems',
        require('./SidebarItems.vue').default
    );

    Vue.component(
        'SidebarHeader',
        require('./SidebarHeader.vue').default
    );
}

