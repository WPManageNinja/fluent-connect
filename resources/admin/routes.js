import Dashboard from './Components/Dashboard.vue';
import Integrations from './Modules/Integrations/Integrations.vue';
import NewIntegration from './Modules/Integrations/NewIntegration.vue';
import ViewIntegration from './Modules/Integrations/ViewIntegration.vue';

import Connectors from './Modules/Connectors/Connectors.vue';
import EditConnector from './Modules/Connectors/EditConnector.vue';

import Logs from './Modules/Logs/Logs.vue';

export default [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            active: 'dashboard'
        }
    },
    {
        name: 'integrations',
        path: '/integrations',
        component: Integrations,
        meta: {
            active: 'integrations'
        }
    },
    {
        path: '/integrations/new',
        name: 'new_integration',
        component: NewIntegration,
        meta: {
            active: 'integrations'
        }
    },
    {
        path: '/integrations/:id/view',
        name: 'view_integration',
        component: ViewIntegration,
        meta: {
            active: 'integrations'
        },
        props: true
    },
    {
        path: '/connectors',
        name: 'connectors',
        component: Connectors,
        meta: {
            active: 'connectors'
        },
        props: true
    },
    {
        path: '/connectors/:feed_id/edit',
        name: 'edit_connector',
        component: EditConnector,
        meta: {
            active: 'connectors'
        },
        props: true
    },
    {
        path: '/logs',
        name: 'logs',
        component: Logs,
        meta: {
            active: 'logs'
        }
    }
];

