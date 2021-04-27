import {createApp} from 'vue'

import ElementPlus, {ElMessage} from 'element-plus'
import 'dayjs/locale/zh-cn'
import locale from 'element-plus/lib/locale/lang/zh-cn'

import state from "./utils/state"
import mixin from "./utils/mixin"
import http from "./utils/http"

window.adminView = {
    name: 'PinAdminView',
    message: ElMessage,
    state,
    createApp: (element, App) => {
        const app = createApp(App)

        app.config.globalProperties.$http = http

        app.mixin(mixin)
        app.use(ElementPlus, {locale})

        app.mount(element)

        return app
    }
}